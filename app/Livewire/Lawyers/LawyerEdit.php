<?php

namespace App\Livewire\Lawyers;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Lawyer;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LawyerEdit extends Component
{
    use WithFileUploads;

    public Lawyer $lawyer;

    public string $name = '';
    public string $phone = '';
    public string $address = '';
    public string $degree = 'ابتدائي';
    public string $bio = '';

    public string $email = '';
    public string $specialization = '';
    public string $license_number = '';
    public string $password = '';
    public string $password_confirmation = '';

    public $profile_image = null;
    public $national_id_image = null;
    public $bar_card_image = null;

    public function mount(Lawyer $lawyer): void
    {
        $user = auth()->user();
        if ($user->role === 'lawyer' && $user->lawyer_id != $lawyer->id) {
            abort(403);
        }

        $this->lawyer = $lawyer;
        $this->name = (string) $lawyer->name;
        $this->phone = (string) $lawyer->phone;
        $this->address = (string) $lawyer->address;
        $this->degree = (string) $lawyer->degree;
        $this->bio = (string) ($lawyer->bio ?? '');

        $this->email = (string) $lawyer->email;
        $this->specialization = (string) $lawyer->specialization;
        $this->license_number = (string) $lawyer->license_number;
    }

    protected function rules(): array
    {
        $isAdmin = auth()->user()->role === 'admin';

        $rules = [
            'name'              => 'required|string|max:255',
            'phone'             => 'required|string|max:20',
            'address'           => 'required|string|max:255',
            'degree'            => 'required|string|max:255',
            'profile_image'     => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'national_id_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'bar_card_image'    => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'bio'               => 'nullable|string',
        ];

        if ($isAdmin) {
            $rules['email']          = 'required|email|unique:lawyers,email,' . $this->lawyer->id;
            $rules['specialization'] = 'required|string|max:255';
            $rules['license_number'] = 'required|string|max:255|unique:lawyers,license_number,' . $this->lawyer->id;
            if (! empty($this->password)) {
                $rules['password'] = 'min:6|confirmed';
            }
        }

        return $rules;
    }

    public function save(): void
    {
        $user = auth()->user();
        if ($user->role === 'lawyer' && $user->lawyer_id != $this->lawyer->id) {
            abort(403);
        }

        $isAdmin = $user->role === 'admin';
        $this->validate();

        $data = [
            'name'    => $this->name,
            'phone'   => $this->phone,
            'address' => $this->address,
            'degree'  => $this->degree,
            'bio'     => $this->bio,
        ];

        if ($isAdmin) {
            $data['email']          = $this->email;
            $data['specialization'] = $this->specialization;
            $data['license_number'] = $this->license_number;
        }

        if ($this->profile_image) {
            $data['profile_image'] = $this->profile_image->store('profile_images', 'public');
        }
        if ($this->national_id_image) {
            $data['national_id_image'] = $this->national_id_image->store('national_ids', 'public');
        }
        if ($this->bar_card_image) {
            $data['bar_card_image'] = $this->bar_card_image->store('bar_cards', 'public');
        }

        $this->lawyer->update($data);

        $userUpdate = ['name' => $this->name];
        if ($isAdmin && ! empty($this->email)) {
            $userUpdate['email'] = $this->email;
        }
        if ($isAdmin && ! empty($this->password)) {
            $userUpdate['password'] = Hash::make($this->password);
        }
        User::where('lawyer_id', $this->lawyer->id)->update($userUpdate);

        session()->flash('success', 'تم تحديث بيانات المحامي بنجاح');
        $this->redirect(route('lawyers.show', $this->lawyer->id), navigate: true);
    }

    public function render()
    {
        return view('livewire.lawyers.lawyer-edit')
            ->title('تعديل بيانات المحامي - ' . $this->lawyer->name . ' | ' . firm_name());
    }
}
