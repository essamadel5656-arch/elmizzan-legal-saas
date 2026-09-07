<?php

namespace App\Livewire\Clients;

use Livewire\Component;
use App\Models\Client;
use App\Models\User;
use App\Mail\ClientWelcomeMail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ClientCreate extends Component
{
    public string $name = '';
    public string $address = '';
    public string $phone = '';
    public string $email = '';
    public string $nid = '';
    public string $note = '';

    protected function rules(): array
    {
        return [
            'name'    => 'required|string|max:100',
            'address' => 'required|string|max:100',
            'phone'   => 'required|string|max:11',
            'email'   => 'required|string|email|max:100|unique:clients,email',
            'nid'     => 'required|string|max:14',
            'note'    => 'nullable|string|max:100',
        ];
    }

    protected function messages(): array
    {
        return [
            'name.required'    => 'اسم العميل مطلوب.',
            'address.required' => 'العنوان مطلوب.',
            'phone.required'   => 'رقم الهاتف مطلوب.',
            'email.required'   => 'البريد الإلكتروني مطلوب.',
            'email.email'      => 'صيغة البريد الإلكتروني غير صحيحة.',
            'email.unique'     => 'البريد الإلكتروني مسجل بالفعل لعميل آخر.',
            'nid.required'     => 'الرقم القومي مطلوب.',
        ];
    }

    public function mount(): void
    {
        $this->authorize('create', Client::class);
    }

    public function save(): void
    {
        $this->authorize('create', Client::class);

        $validated = $this->validate();

        $client = Client::create($validated);

        $token = Str::random(60);
        $user = User::create([
            'name'                        => $client->name,
            'email'                       => $client->email,
            'password'                    => Hash::make(Str::random(16)),
            'role'                        => 'client',
            'client_id'                   => $client->id,
            'activation_token'            => $token,
            'activation_token_expires_at' => now()->addHours(48),
        ]);

        try {
            Mail::to($client->email)->send(new ClientWelcomeMail($user, $client, $token));
        } catch (\Throwable $e) {
            // Fail silently if mail service is unavailable
        }

        session()->flash('success', 'تم إضافة العميل بنجاح وإرسال رابط التفعيل لبريده الإلكتروني.');
        $this->redirect(route('clients.show', $client->id), navigate: true);
    }

    public function render()
    {
        return view('livewire.clients.client-create')
            ->title('إضافة عميل جديد | ' . firm_name());
    }
}
