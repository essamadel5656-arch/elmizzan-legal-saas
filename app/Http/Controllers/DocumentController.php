<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\LegalCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    private function baseDocumentsQuery()
    {
        $user = auth()->user();

        return Document::query()
            ->when($user->role === 'lawyer', function ($query) use ($user) {
                $query->whereHas('case', function ($q) use ($user) {
                    $q->where('lawyer_id', $user->lawyer_id);
                });
            });
    }

    public function index(Request $request)
    {
        $documents = $this->baseDocumentsQuery()
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where('title', 'LIKE', '%' . $request->search . '%');
            })
            ->latest()
            ->get();

        return view('document.index', compact('documents'));
    }

    public function create(LegalCase $case)
    {
        $user = auth()->user();

        if ($user->role === 'lawyer' && $case->lawyer_id !== $user->lawyer_id) {
            abort(403, 'غير مصرح لك بإضافة مستندات لهذه القضية.');
        }

        return view('document.add_documents', compact('case'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'document_name' => 'required|string|max:255',
            'document_type' => 'required|in:contract,report,attachment',
            'case_id'       => 'required|exists:cases,id',
            'file'          => 'required|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
        ]);

        $user   = auth()->user();
        $caseId = $request->case_id;

        if ($user->role === 'lawyer') {
            $case = LegalCase::findOrFail($caseId);
            if ($case->lawyer_id !== $user->lawyer_id) {
                abort(403, 'غير مصرح لك بإضافة مستندات لهذه القضية.');
            }
        }

        $filePath = $request->file('file')->store('documents', 'public');

        Document::create([
            'title'         => $request->document_name,
            'type'          => $request->document_type,
            'file_path'     => $filePath,
            'case_id'       => $caseId,
        ]);

        return redirect()->route('cases.show', $caseId)
            ->with('success', 'تم إضافة المستند بنجاح ✅');
    }

    public function show($id)
    {
        $document = $this->baseDocumentsQuery()->with('case')->findOrFail($id);
        return view('document.show', compact('document'));
    }

    public function edit($id)
    {
        $document = $this->baseDocumentsQuery()->with('case')->findOrFail($id);
        return view('document.edit', compact('document'));
    }

    public function update(Request $request, $id)
    {
        $document = $this->baseDocumentsQuery()->findOrFail($id);

        $request->validate([
            'title'         => 'required|string|max:255',
            'document_type' => 'required|in:contract,report,attachment',
            'description'   => 'nullable|string',
            'file'          => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
        ]);

        $document->title         = $request->title;
        $document->document_type = $request->document_type;
        $document->description   = $request->description;

        if ($request->hasFile('file')) {
            if ($document->file_path && Storage::disk('public')->exists($document->file_path)) {
                Storage::disk('public')->delete($document->file_path);
            }
            $document->file_path = $request->file('file')->store('documents', 'public');
        }

        $document->save();

        return redirect()->route('document.show', $document->id)
            ->with('success', 'تم تحديث المستند بنجاح ✅');
    }

    public function destroy($id)
    {
        $document = $this->baseDocumentsQuery()->findOrFail($id);

        if ($document->file_path && Storage::disk('public')->exists($document->file_path)) {
            Storage::disk('public')->delete($document->file_path);
        }

        $document->delete();

        return redirect()->route('document.index')
            ->with('success', 'تم حذف المستند بنجاح ✅');
    }
}