<?php

namespace App\Livewire\Lawyers;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Lawyer;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\LawyerWelcomeMail;

class LawyerCreate extends Component
{
    use WithFileUploads;

    public string $name = '';
    public string $phone = '';
    public string $specialization = '';
    public string $license_number = '';
    public string $address = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public string $degree = 'ابتدائي';
    public string $bio = '';

    public $bar_card_image = null;
    public $profile_image = null;
    public $national_id_image = null;

    protected function rules(): array
    {
        return [
            'name'              => 'required|string|min:3|max:255',
            'phone'             => ['required', 'string', 'regex:/^(010|011|012|015)[0-9]{8}$/'],
            'specialization'    => 'required|string|min:3|max:100',
            'license_number'    => 'required|string|max:50|unique:lawyers,license_number',
            'address'           => 'required|string|min:10|max:500',
            'email'             => 'required|email|max:255|unique:lawyers,email|unique:users,email',
            'password'          => 'required|string|min:8|max:20|confirmed',
            'degree'            => 'required|string|in:نقض,استئناف,ابتدائي,جدول_عام',
            'bio'               => 'required|string|min:20|max:1000',
            'bar_card_image'    => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'profile_image'     => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'national_id_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ];
    }

    protected function messages(): array
    {
        return [
            'phone.regex'        => 'رقم الموبايل غير صحيح، يجب أن يكون رقم مصري مكون من 11 رقم.',
            'email.email'        => 'البريد الإلكتروني الذي أدخلته غير صالح.',
            'email.unique'       => 'البريد الإلكتروني مسجل بالفعل.',
            'license_number.unique' => 'رقم القيد مسجل بالفعل.',
            'password.confirmed' => 'كلمة المرور غير متطابقة مع حقل التأكيد.',
            'bar_card_image.required' => 'صورة كارنيه المحاماة مطلوبة.',
        ];
    }

    public function mount(): void
    {
        $this->authorize('create', Lawyer::class);
    }

    public function generateAiPassword(): void
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $len = rand(10, 16);
        $pw = '';
        for ($i = 0; $i < $len; $i++) {
            $pw .= $chars[random_int(0, strlen($chars) - 1)];
        }
        $this->password = $pw;
        $this->password_confirmation = $pw;
    }

    public function save(): void
    {
        $this->authorize('create', Lawyer::class);

        $this->validate();

        $profileImagePath = '';
        if ($this->profile_image) {
            $profileImagePath = $this->profile_image->store('profile_images', 'public');
        }

        $nationalIdPath = '';
        if ($this->national_id_image) {
            $nationalIdPath = $this->national_id_image->store('national_ids', 'public');
        }

        $barCardPath = $this->bar_card_image->store('bar_cards', 'public');
        $plainPassword = $this->password;

        DB::transaction(function () use ($profileImagePath, $nationalIdPath, $barCardPath, $plainPassword) {
            $lawyer = Lawyer::create([
                'name'              => $this->name,
                'phone'             => $this->phone,
                'specialization'    => $this->specialization,
                'license_number'    => $this->license_number,
                'address'           => $this->address,
                'email'             => $this->email,
                'degree'            => $this->degree,
                'profile_image'     => $profileImagePath,
                'national_id_image' => $nationalIdPath,
                'bar_card_image'    => $barCardPath,
                'bio'               => $this->bio,
            ]);

            User::create([
                'name'      => $this->name,
                'email'     => $this->email,
                'password'  => Hash::make($plainPassword),
                'role'      => 'lawyer',
                'lawyer_id' => $lawyer->id,
            ]);

            try {
                Mail::to($lawyer->email)->send(new LawyerWelcomeMail($lawyer, $plainPassword));
            } catch (\Throwable $e) {
                // Fail silently if mail provider is unavailable
            }
        });

        session()->flash('success', 'تم حفظ المحامي بنجاح وإرسال بيانات الحساب لإيميله.');
        $this->redirect(route('lawyers.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.lawyers.lawyer-create')
            ->title('إضافة محامي جديد | ' . firm_name());
    }
}
