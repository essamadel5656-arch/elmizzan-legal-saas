<?php

namespace App\Livewire\Payments;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\LegalCase;
use App\Models\ClientPayment;
use App\Models\Appointment;
use App\Models\User;
use App\Notifications\InPersonPaymentScheduledNotification;

class ClientPaymentCreate extends Component
{
    use WithFileUploads;

    public LegalCase $case;

    public string $mode          = 'in_person';  // 'in_person' | 'transfer'
    public float  $amount        = 0;
    public string $payment_date  = '';
    public string $reference     = '';
    public $receipt              = null;
    public string $notes         = '';

    // Payment settings (loaded from settings table)
    public string $bankName      = '';
    public string $bankIban      = '';
    public string $instapay      = '';
    public string $mobileWallet  = '';

    protected function rules(): array
    {
        $baseRules = [
            'amount'       => 'required|numeric|min:0.01',
            'notes'        => 'nullable|string|max:1000',
        ];

        if ($this->mode === 'in_person') {
            $baseRules['payment_date'] = 'required|date|after_or_equal:today';
        } else {
            $baseRules['reference'] = 'required|string|max:255';
            $baseRules['receipt']   = 'nullable|file|mimes:pdf,jpg,jpeg,png,webp|max:5120';
        }

        return $baseRules;
    }

    protected function messages(): array
    {
        return [
            'amount.required'       => 'المبلغ مطلوب.',
            'amount.min'            => 'المبلغ يجب أن يكون أكبر من صفر.',
            'payment_date.required' => 'تاريخ الدفع مطلوب.',
            'payment_date.after_or_equal' => 'يجب أن يكون تاريخ الدفع اليوم أو في المستقبل.',
            'reference.required'    => 'رقم المرجع / رقم العملية مطلوب.',
            'receipt.mimes'         => 'صيغ الإيصال المقبولة: pdf, jpg, png, webp.',
        ];
    }

    public function mount(LegalCase $case): void
    {
        $this->case        = $case;
        $this->bankName    = tenant_setting('bank_name', '');
        $this->bankIban    = tenant_setting('bank_iban', '');
        $this->instapay    = tenant_setting('instapay_address', '');
        $this->mobileWallet= tenant_setting('mobile_wallet', '');
    }

    public function submit(): void
    {
        $user = auth()->user();
        abort_unless($user->isClient(), 403, 'هذه الصفحة للموكلين فقط.');

        $this->validate();

        $client    = $user->client;
        $receiptPath = null;

        if ($this->mode === 'in_person') {
            // Option A: Create appointment record
            Appointment::create([
                'case_id'    => $this->case->id,
                'date'       => $this->payment_date,
                'time'       => '10:00',
                'notes'      => 'دفعة نقدية بالمكتب — ' . number_format($this->amount) . ' ج.م.' . ($this->notes ? ' — ' . $this->notes : ''),
                'client_id'  => $client?->id,
            ]);

            $payment = ClientPayment::create([
                'case_id'      => $this->case->id,
                'client_id'    => $client->id,
                'mode'         => 'in_person',
                'amount'       => $this->amount,
                'payment_date' => $this->payment_date,
                'status'       => 'pending',
                'notes'        => $this->notes ?: null,
            ]);

            // Notify lead lawyer + admins
            $this->notifyStaff($payment);
            session()->flash('payment_success', 'تم تسجيل موعد الدفع بنجاح. سنراك في ' . $this->payment_date . '!');

        } else {
            // Option B: Transfer — upload receipt
            if ($this->receipt) {
                $receiptPath = $this->receipt->store('payment_receipts', 'public');
            }

            $payment = ClientPayment::create([
                'case_id'          => $this->case->id,
                'client_id'        => $client->id,
                'mode'             => 'transfer',
                'amount'           => $this->amount,
                'reference_number' => $this->reference,
                'receipt_path'     => $receiptPath,
                'status'           => 'pending_verification',
                'notes'            => $this->notes ?: null,
            ]);

            // Notify lead lawyer + admins
            $this->notifyStaff($payment);

            session()->flash('payment_success', 'تم إرسال إيصال التحويل بنجاح. ستتم مراجعته والتأكيد خلال 24 ساعة.');
        }

        $this->redirect(route('client-portal.dashboard'), navigate: true);
    }

    private function notifyStaff(ClientPayment $payment): void
    {
        // Lead lawyer
        $leadLawyerPivot = $this->case->lawyers()->wherePivot('role', 'lead')->first();
        if ($leadLawyerPivot) {
            $lawyerUser = User::where('lawyer_id', $leadLawyerPivot->id)->first();
            $lawyerUser?->notify(new InPersonPaymentScheduledNotification($payment));
        }

        // Admins
        User::where('role', 'admin')->each(fn($a) => $a->notify(new InPersonPaymentScheduledNotification($payment)));
    }

    public function render()
    {
        return view('livewire.payments.client-payment-create')
            ->title('تسجيل دفعة | ' . firm_name());
    }
}
