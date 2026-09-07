<?php

namespace App\Http\Controllers;

use App\Mail\NewCaseMail;
use App\Models\Client;
use App\Models\Court;
use App\Models\CourtLevel;
use App\Models\Jurisdiction;
use App\Models\Lawyer;
use App\Models\LegalCase;
use App\Models\User;
use App\Notifications\CaseAssignedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail; // 🌟 استدعاء الـ Mail Facade
use Illuminate\Support\Facades\Storage;             // 🌟 استدعاء ملف الإيميل الجديد

class LegalCaseController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $user = auth()->user();

        $cases = LegalCase::with(['court'])
            // If user is a lawyer, filter cases by their lawyer_id
            ->when($user->role === 'lawyer', function ($query) use ($user) {
                return $query->where('lawyer_id', $user->lawyer_id);
            })
            ->when($search, function ($query) use ($search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('case_number', 'like', "%{$search}%")
                        ->orWhere('rival_name', 'like', "%{$search}%")
                        ->orWhereHas('client', function ($subQ) use ($search) {
                            $subQ->where('name', 'like', "%{$search}%");
                        });
                });
            })
            ->get();

        $jurisdictions = Jurisdiction::select('id', 'name')->get();

        return view('cases.index', compact('cases', 'jurisdictions', 'search'));
    }

    public function create()
    {
        $this->authorize('create', LegalCase::class);

        $lawyers = Lawyer::select('id', 'name')->get();
        $jurisdictions = Jurisdiction::select('id', 'name')->get();
        $courts = Court::select('id', 'name', 'jurisdiction_id')->get();
        $court_levels = CourtLevel::select('id', 'name')->get();
        $clients = Client::select('id', 'name', 'phone', 'nid', 'address')->get();

        return view('cases.create', compact(
            'lawyers', 'jurisdictions', 'courts', 'court_levels', 'clients'
        ));
    }

    public function store(Request $request)
    {
        $this->authorize('create', LegalCase::class);

        $messages = [
            'client_id.required' => 'برجاء اختيار العميل صاحب القضية.',
            'client_id.exists' => 'العميل المختار غير مسجل في النظام.',
            'case_number.required' => 'برجاء إدخال رقم القضية.',
            'lawyer_id.required' => 'برجاء اختيار المحامي المسؤول عن القضية.',
            'lawyer_id.exists' => 'المحامي المختار غير مسجل بالنظام.',
            'jurisdiction_id.required' => 'جهة التقاضي مطلوبة.',
            'court_level_id.required' => 'درجة التقاضي مطلوبة.',
            'status.required' => 'حالة القضية مطلوبة.',
            'rival_name.required' => 'اسم الخصم مطلوب.',
            'rival_number.required' => 'رقم الخصم مطلوب.',
            'rival_address.required' => 'عنوان الخصم مطلوب.',
            'rival_nid.required' => 'الرقم القومي للخصم مطلوب.',
            'case_file.file' => 'الملف المرفوع يجب أن يكون ملفاً صحيحاً.',
            'case_file.mimes' => 'صيغ الملفات المسموحة هي: pdf, doc, docx, png, jpg.',
            'case_file.max' => 'حجم الملف لا يجب أن يتخطى 2 ميجابايت.',
        ];

        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'case_number' => 'required|string',
            'lawyer_id' => auth()->user()->role === 'lawyer' ? 'nullable' : 'required|exists:lawyers,id',
            'jurisdiction_id' => 'required|exists:jurisdictions,id',
            'court_level_id' => 'required|exists:court_levels,id',
            'circuit' => 'nullable|string|max:255',
            'status' => 'required|string',
            'description' => 'nullable|string',
            'costs' => 'nullable|numeric',
            'total_costs' => 'nullable|numeric',
            'deposit' => 'nullable|numeric',
            'Previous_procedure' => 'nullable|string',
            'final_decision' => 'nullable|string',
            'notes' => 'nullable|string',
            'rival_name' => 'required|string',
            'rival_number' => 'required|string',
            'rival_address' => 'required|string',
            'rival_nid' => 'required|string',
            'case_file' => 'nullable|file|mimes:pdf,doc,docx,png,jpg|max:2048',
        ], $messages);

        $data = $request->only([
            'case_number', 'status', 'description', 'costs', 'total_costs',
            'deposit', 'Previous_procedure', 'procuration', 'final_decision',
            'notes', 'rival_name', 'rival_number', 'rival_address', 'rival_nid',
            'lawyer_id', 'jurisdiction_id', 'court_level_id', 'circuit',
        ]);

        $data['court_id'] = $request->input('court_id');

        $data['case_file'] = null;
        if ($request->hasFile('case_file')) {
            $data['case_file'] = $request->file('case_file')->store('cases_attachments', 'public');
        }

        $case = LegalCase::create([
            'case_number' => $data['case_number'],
            'status' => $data['status'],
            'description' => $data['description'] ?? null,
            'costs' => $data['costs'] ?? null,
            'total_costs' => $data['total_costs'] ?? null,
            'deposit' => $data['deposit'] ?? null,
            'Previous_procedure' => $data['Previous_procedure'] ?? 'لا يوجد إشعار سابق',
            'final_decision' => $data['final_decision'] ?? 'لم يصدر حكم بعد',
            'notes' => $data['notes'] ?? 'لا توجد ملاحظات',
            'procuration' => $data['procuration'] ?? 'لا يوجد',
            'rival_name' => $data['rival_name'],
            'rival_number' => $data['rival_number'],
            'rival_address' => $data['rival_address'],
            'rival_nid' => $data['rival_nid'],
            'court_id' => $data['court_id'],
            'jurisdiction_id' => $data['jurisdiction_id'],
            'court_level_id' => $data['court_level_id'],
            'circuit' => $data['circuit'] ?? null,
            'lawyer_id' => auth()->user()->role === 'lawyer' ? auth()->user()->lawyer_id : $request->lawyer_id,
            'case_file' => $data['case_file'],
        ]);

        DB::table('client_case')->insert([
            'case_id' => $case->id,
            'client_id' => $request->client_id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 🌟 إرسال إيميل القضية الجديدة للمحامي المسؤول في الخلفية (Queue)
        $lawyer = Lawyer::find($case->lawyer_id);
        if ($lawyer && $lawyer->email) {
            Mail::to($lawyer->email)->send(new NewCaseMail($case));
        }

        return redirect()->route('cases.index')->with('success', 'تم إضافة القضية بنجاح');
    }

    public function show(LegalCase $case)
    {
        $this->authorize('view', $case);

        // تم تغيير 'court.court_level' إلى 'courtLevel' لتُقرأ من القضية مباشرة
        $case = LegalCase::with([
            'clients',
            'court.jurisdiction',
            'courtLevel',
            'lawyers',
            'appointments',
            'documents',
        ])->findOrFail($case->id);

        return view('cases.show', compact('case'));
    }

    public function edit(LegalCase $case)
    {
        $this->authorize('update', $case);

        $case->load(['clients', 'lawyers', 'court.jurisdiction']);

        $courts = Court::all();
        $lawyers = Lawyer::all();
        $jurisdictions = Jurisdiction::all();
        $court_levels = CourtLevel::all();

        // المحامي يشوف عملاء قضاياه بس — الأدمن يشوف الكل
        if (auth()->user()->role === 'lawyer') {
            $lawyerCaseIds = LegalCase::where('lawyer_id', auth()->user()->lawyer_id)
                ->pluck('id');

            $clients = Client::whereHas('cases', function ($query) use ($lawyerCaseIds) {
                $query->whereIn('cases.id', $lawyerCaseIds);
            })->select('id', 'name', 'phone', 'nid', 'address')->get();
        } else {
            $clients = Client::select('id', 'name', 'phone', 'nid', 'address')->get();
        }

        return view('cases.edit', compact('case', 'courts', 'lawyers', 'jurisdictions', 'court_levels', 'clients'));
    }

    public function update(Request $request, LegalCase $case)
    {
        $this->authorize('update', $case);

        $messages = [
            'client_ids.required' => 'برجاء اختيار عميل واحد على الأقل صاحب القضية.',
            'client_ids.array' => 'تنسيق بيانات العملاء غير صحيح.',
            'lawyer_ids.required' => 'برجاء اختيار محامي واحد على الأقل مسؤول عن القضية.',
            'case_number.required' => 'برجاء إدخال رقم القضية.',
            'jurisdiction_id.required' => 'جهة التقاضي مطلوبة.',
            'court_level_id.required' => 'درجة التقاضي مطلوبة.',
            'status.required' => 'حالة القضية مطلوبة.',
            'rival_name.required' => 'اسم الخصم مطلوب.',
            'case_file.mimes' => 'صيغ الملفات المسموحة هي: pdf, doc, docx, png, jpg.',
            'case_file.max' => 'حجم الملف لا يجب أن يتخطى 2 ميجابايت.',
        ];

        $request->validate([
            'client_ids' => 'required|array',
            'client_ids.*' => 'exists:clients,id',
            'lawyer_ids' => 'required|array',
            'lawyer_ids.*' => 'exists:lawyers,id',
            'lawyer_roles' => 'nullable|array',
            'case_number' => 'required|string',
            'jurisdiction_id' => 'required|exists:jurisdictions,id',
            'court_level_id' => 'required|exists:court_levels,id',
            'circuit' => 'nullable|string|max:255',
            'status' => 'required|string',
            'court_id' => 'required|exists:courts,id',
            'judicial_authority_type' => 'nullable|string',
            'description' => 'nullable|string',
            'costs' => 'nullable|numeric',
            'total_costs' => 'nullable|numeric',
            'deposit' => 'nullable|numeric',
            'Previous_procedure' => 'nullable|string',
            'final_decision' => 'nullable|string',
            'notes' => 'nullable|string',
            'rival_name' => 'required|string',
            'rival_number' => 'required|string',
            'rival_address' => 'required|string',
            'rival_nid' => 'required|string',
            'case_file' => 'nullable|file|mimes:pdf,doc,docx,png,jpg|max:2048',
        ], $messages);

        $data = $request->only([
            'case_number', 'status', 'description', 'costs', 'total_costs',
            'deposit', 'Previous_procedure', 'final_decision', 'court_level_id',
            'notes', 'rival_name', 'rival_number', 'rival_address', 'rival_nid',
            'jurisdiction_id', 'court_id', 'circuit',
            'judicial_authority_type',
        ]);

        if ($request->hasFile('case_file')) {
            if ($case->case_file && Storage::disk('public')->exists($case->case_file)) {
                Storage::disk('public')->delete($case->case_file);
            }
            $data['case_file'] = $request->file('case_file')->store('cases_attachments', 'public');
        }

        $case->update($data);

        $case->clients()->sync($request->input('client_ids', []));

        if ($request->has('lawyer_ids')) {
            $lawyersSyncData = [];
            $roleMapping = [
                'محامي رئيسي' => 'lead',
                'مساعد' => 'assistant',
                'مستشار' => 'consultant',
                'lead' => 'lead',
                'assistant' => 'assistant',
                'consultant' => 'consultant',
            ];

            foreach ($request->lawyer_ids as $lawyerId) {
                $incomingRole = $request->lawyer_roles[$lawyerId] ?? 'assistant';
                $dbRole = $roleMapping[$incomingRole] ?? 'assistant';
                $lawyersSyncData[$lawyerId] = ['role' => $dbRole];
            }

            $case->lawyers()->sync($lawyersSyncData);

            // 🔔 Fire CaseAssignedNotification for each assigned lawyer's user account
            foreach ($lawyersSyncData as $lawyerId => $pivot) {
                $user = User::where('lawyer_id', $lawyerId)->first();
                if ($user) {
                    $user->notify(new CaseAssignedNotification($case, $pivot['role']));
                }
            }
        }

        return redirect()->route('cases.index')->with('success', 'تم تحديث القضية بنجاح');
    }

    public function getCourts()
    {
        $courts = Court::select('id', 'name', 'jurisdiction_id')->orderBy('name')->get();

        return response()->json($courts);
    }

    public function getcaseDetails(LegalCase $case)
    {
        return response()->json([
            'lawyers' => $case->lawyers()->get(),
            'appointments' => $case->appointments()->get(),
            'documents' => $case->documents()->get(),
        ]);
    }

    public function destroy(LegalCase $case)
    {
        $this->authorize('delete', $case);

        if ($case->case_file && Storage::disk('public')->exists($case->case_file)) {
            Storage::disk('public')->delete($case->case_file);
        }

        $case->delete();

        return redirect()->route('cases.index')->with('success', 'تم حذف القضية بنجاح');
    }
}
