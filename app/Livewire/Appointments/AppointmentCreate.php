<?php

namespace App\Livewire\Appointments;

use Livewire\Component;
use App\Models\Appointment;
use App\Models\LegalCase;
use App\Models\User;
use App\Notifications\CaseSessionUpdatedNotification;

class AppointmentCreate extends Component
{
    public LegalCase $case;
    public string $date = '';
    public string $time = '';
    public string $notes = '';

    protected function rules(): array
    {
        return [
            'date'  => 'required|date',
            'time'  => 'required',
            'notes' => 'nullable|string',
        ];
    }

    protected function messages(): array
    {
        return [
            'date.required' => 'تاريخ الموعد مطلوب.',
            'date.date'     => 'صيغة التاريخ غير صحيحة.',
            'time.required' => 'وقت الموعد مطلوب.',
        ];
    }

    public function mount(LegalCase $case): void
    {
        if (auth()->user()->role === 'lawyer' && $case->lawyer_id != auth()->user()->lawyer_id) {
            abort(403);
        }

        $this->case = $case;
    }

    public function save(): void
    {
        $this->validate();

        $appointment = Appointment::create([
            'case_id' => $this->case->id,
            'date'    => $this->date,
            'time'    => $this->time,
            'notes'   => $this->notes ?: '',
        ]);

        // 🔔 Notify all lawyers linked to this case
        $this->case->load('lawyers');
        foreach ($this->case->lawyers as $lawyer) {
            $user = User::where('lawyer_id', $lawyer->id)->first();
            if ($user) {
                $user->notify(new CaseSessionUpdatedNotification($appointment, 'created'));
            }
        }

        if ($this->case->lawyer_id) {
            $leadUser = User::where('lawyer_id', $this->case->lawyer_id)->first();
            if ($leadUser && ! $this->case->lawyers->pluck('id')->contains($this->case->lawyer_id)) {
                $leadUser->notify(new CaseSessionUpdatedNotification($appointment, 'created'));
            }
        }

        session()->flash('success', 'تم حفظ الموعد بنجاح');
        $this->redirect(route('cases.show', $this->case->id), navigate: true);
    }

    public function render()
    {
        return view('livewire.appointments.appointment-create')
            ->title('إضافة موعد جديد | ' . firm_name());
    }
}
