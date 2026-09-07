<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use App\Models\Lawyer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Mail;
use App\Mail\LawyerWelcomeMail;

class LawyerController extends Controller
{
    public function index(Request $request)
    {
        $lawyers = Lawyer::when($request->search, function ($query) use ($request) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('id', $request->search);
        })->get();

        return view('lawyers.index', compact('lawyers'));
    }

    public function create()
    {
        // 🔒 Only admin can create new lawyer accounts
        $this->authorize('create', Lawyer::class);

        return view('lawyers.lawyer_create');
    }

    public function store(Request $request)
    {
        // 🔒 Only admin can create new lawyer accounts
        $this->authorize('create', Lawyer::class);

        $request->validate([
        'name'              => 'required|string|min:3|max:255',
        'phone'             => ['required', 'string', 'regex:/^(010|011|012|015)[0-9]{8}$/'],
        'specialization'    => 'required|string|min:3|max:100',
        'license_number'    => 'required|string|max:50|unique:lawyers,license_number',
        'address'           => 'required|string|min:10|max:500',
        'email'             => 'required|email|max:255|unique:lawyers,email|unique:users,email',
        'password'          => 'required|string|min:8|max:20|confirmed',
        'degree'            => 'required|string|in:نقض,استئناف,ابتدائي,جدول_عام',
        'bio'               => 'required|string|min:20|max:1000',
        'bar_card_image'    => 'required|image|mimes:jpeg,png,jpg|max:2048', // الكارنيه إجباري في الفرونت يبقا إجباري هنا
        'profile_image'     => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        'national_id_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
    ], [
        'phone.regex' => 'رقم الموبايل غير صحيح، يجب أن يكون رقم مصري مكون من 11 رقم.',
        'email.email' => 'البريد الإلكتروني الذي أدخلته غير صالح أو النطاق غير موجود.',
        'password.confirmed' => 'كلمة المرور غير متطابقة مع حقل التأكيد.',
    ]);

    $profileImagePath = '';
    if ($request->hasFile('profile_image')) {
        $profileImagePath = $request->file('profile_image')->store('profile_images', 'public');
    }

    $nationalIdPath = '';
    if ($request->hasFile('national_id_image')) {
        $nationalIdPath = $request->file('national_id_image')->store('national_ids', 'public');
    }

    $barCardPath = $request->file('bar_card_image')->store('bar_cards', 'public');

    $plainPassword = $request->password;

    DB::transaction(function () use ($request, $profileImagePath, $nationalIdPath, $barCardPath, $plainPassword) {

        $lawyer = Lawyer::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'specialization' => $request->specialization,
            'license_number' => $request->license_number,
            'address' => $request->address,
            'email' => $request->email,
            'degree' => $request->degree,
            'profile_image' => $profileImagePath,
            'national_id_image' => $nationalIdPath,
            'bar_card_image' => $barCardPath,
            'bio' => $request->bio
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($plainPassword),
            'role' => 'lawyer',
            'lawyer_id' => $lawyer->id,
        ]);

        Mail::to($lawyer->email)->send(new LawyerWelcomeMail($lawyer, $plainPassword));
    });

    return redirect()->route('lawyers.index')->with('success', 'تم حفظ المحامي بنجاح وإرسال بيانات الحساب لإيميله.');
}

public function show($id)
{
    $lawyers = Lawyer::findOrFail($id);
    return view('lawyers.show', compact('lawyers'));
}
    public function edit($id)
    {
        // المحامي يعدل نفسه بس
        if (auth()->user()->role === 'lawyer' && auth()->user()->lawyer_id != $id) {
            abort(403);
        }

        $lawyers = Lawyer::findOrFail($id);
        return view('lawyers.edit', compact('lawyers'));
    }

    public function update(Request $request, $id)
    {
        if (auth()->user()->role === 'lawyer' && auth()->user()->lawyer_id != $id) {
            abort(403);
        }

        $lawyers = Lawyer::findOrFail($id);
        $isAdmin = auth()->user()->role === 'admin';

        $rules = [
            'name'             => 'required|string|max:255',
            'phone'            => 'required|string|max:20',
            'address'          => 'required|string|max:255',
            'degree'           => 'required|string|max:255',
            'profile_image'    => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'national_id_image'=> 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'bar_card_image'   => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'bio'              => 'nullable|string',
        ];

        // الحقول المقفولة على المحامي (الإيميل، التخصص، رقم القيد) تتفحص فقط لو أدمن
        if ($isAdmin) {
            $rules['email']          = 'required|email|unique:lawyers,email,' . $id;
            $rules['specialization'] = 'required|string|max:255';
            $rules['license_number'] = 'required|string|max:255|unique:lawyers,license_number,' . $id;
        }

        // قواعد الباسورد للأدمن بس
        if ($isAdmin && $request->filled('password')) {
            $rules['password'] = 'min:6|confirmed';
        }

        $request->validate($rules);

        $data = [
            'name'    => $request->name,
            'phone'   => $request->phone,
            'address' => $request->address,
            'degree'  => $request->degree,
            'bio'     => $request->bio,
        ];

        if ($isAdmin) {
            // الأدمن بس يقدر يعدل هذه الحقول
            $data['email']          = $request->email;
            $data['specialization'] = $request->specialization;
            $data['license_number'] = $request->license_number;
        }

        if ($request->hasFile('profile_image')) {
            $data['profile_image'] = $request->file('profile_image')->store('profile_images', 'public');
        }
        if ($request->hasFile('national_id_image')) {
            $data['national_id_image'] = $request->file('national_id_image')->store('national_ids', 'public');
        }
        if ($request->hasFile('bar_card_image')) {
            $data['bar_card_image'] = $request->file('bar_card_image')->store('bar_cards', 'public');
        }

        $lawyers->update($data);

        User::where('lawyer_id', $id)->update([
            'name' => $request->name,
        ]);

        // تحديث الباسورد في جدول users (للأدمن بس لو كتب باسورد جديد)
        if ($isAdmin && $request->filled('password')) {
            User::where('lawyer_id', $id)->update([
                'password' => Hash::make($request->password),
            ]);
        }

        // تحديث الإيميل في جدول users لو الأدمن غيّره (لازم يتزامن مع جدول users لتسجيل الدخول)
        if ($isAdmin && $request->filled('email')) {
            User::where('lawyer_id', $id)->update([
                'email' => $request->email,
            ]);
        }

        return redirect()->back()->with('success', 'تم تحديث بيانات المحامي بنجاح');
    }

    public function destroy($id)
    {
        // 🔒 Only admin can delete lawyer records
        Gate::authorize('manage-lawyers');

        $lawyers = Lawyer::findOrFail($id);
        $lawyerName = $lawyers->name;
        $lawyers->delete();
        return redirect()->route('lawyers.index')->with('success', 'تم حذف المحامي ' . $lawyerName . ' بنجاح');
    }
}
