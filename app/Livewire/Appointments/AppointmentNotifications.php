<?php

namespace App\Livewire\Appointments;

use Livewire\Component;
use App\Models\Appointment;

class AppointmentNotifications extends Component
{
    public function render()
    {
        $user = auth()->user();

        $appointments = Appointment::with(['case.court'])
            ->when($user && $user->role === 'lawyer', function ($query) use ($user) {
                return $query->whereHas('case', function ($q) use ($user) {
                    $q->where('lawyer_id', $user->lawyer_id);
                });
            })
            ->where('date', '>=', now()->startOfDay())
            ->where('date', '<=', now()->addDays(7)->endOfDay())
            ->orderBy('date', 'asc')
            ->orderBy('time', 'asc')
            ->get();

        return view('livewire.appointments.appointment-notifications', [
            'appointments' => $appointments,
        ])->title('الإشعارات والمواعيد | ' . firm_name());
    }
}
