<?php

namespace App\Livewire\Appointments;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Appointment;

class AppointmentIndex extends Component
{
    use WithPagination;

    public string $search = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function clearSearch(): void
    {
        $this->search = '';
        $this->resetPage();
    }

    public function delete(int $id): void
    {
        $appointment = Appointment::with('case')->findOrFail($id);
        
        $user = auth()->user();
        if ($user->role === 'lawyer' && $appointment->case?->lawyer_id != $user->lawyer_id) {
            abort(403);
        }

        $appointment->delete();
        session()->flash('success', 'تم حذف الموعد بنجاح');
    }

    public function render()
    {
        $user = auth()->user();

        $appointments = Appointment::with(['case.court'])
            ->when($user->role === 'lawyer', function ($query) use ($user) {
                return $query->whereHas('case', function ($q) use ($user) {
                    $q->where('lawyer_id', $user->lawyer_id);
                });
            })
            ->when($this->search, function ($query) {
                $query->whereHas('case', function ($q) {
                    $q->where('case_number', 'LIKE', '%' . $this->search . '%');
                });
            })
            ->orderBy('date', 'desc')
            ->orderBy('time', 'desc')
            ->get();

        return view('livewire.appointments.appointment-index', [
            'appointments' => $appointments,
        ])->title('إدارة المواعيد | ' . firm_name());
    }
}
