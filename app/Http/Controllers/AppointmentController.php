<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\User;
use App\Notifications\CaseSessionUpdatedNotification;
use Illuminate\Http\Request;
use App\Models\LegalCase;

class AppointmentController extends Controller
{
    private function baseAppointmentsQuery()
    {
        $user = auth()->user();

        return Appointment::with(['case'])
            ->when($user->role === 'lawyer', function ($query) use ($user) {
                return $query->whereHas('case', function ($q) use ($user) {
                    $q->where('lawyer_id', $user->lawyer_id);
                });
        });
    }

    public function index(Request $request)
    {
        // Build the base query with case relationship
        $query = $this->baseAppointmentsQuery();
        
        // Apply search filter by case number if search term is provided
        $search = $request->input('search');
        if ($search) {
            // Filter appointments where the related case's case_number matches the search term (LIKE query)
            $query->whereHas('case', function ($q) use ($search) {
                $q->where('case_number', 'LIKE', '%' . $search . '%');
            });
        }
        
        // Retrieve filtered appointments
        $appointments = $query->get();
        
        // Return view with appointments and search term for display
        return view('appointments.index', compact('appointments', 'search'));
    }

    public function notifications()
    {
        $appointments = $this->baseAppointmentsQuery()
            ->where('date', '>=', now()->startOfDay())
            ->where('date', '<=', now()->addDays(7)->endOfDay())
            ->get();

        return view('appointments.notifications', compact('appointments'));
    }

    public function create(LegalCase $case)
    {
        // تأكيد إن المحامي بس يضيف موعد لقضيته
        if (auth()->user()->role === 'lawyer' && $case->lawyer_id != auth()->user()->lawyer_id) {
            abort(403);
        }

        return view('appointments.create', compact('case'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'case_id' => 'required|exists:cases,id',
            'date'    => 'required|date',
            'time'    => 'required|date_format:H:i',
            'notes'   => 'nullable|string',
        ]);

        $appointment = Appointment::create([
            'case_id' => $validated['case_id'],
            'date'    => $validated['date'],
            'time'    => $validated['time'],
            'notes'   => $validated['notes'] ?? '',
        ]);

        // 🔔 Notify all lawyers linked to this case
        $this->notifyLawyersOnCase($appointment, 'created');

        return redirect()->route('cases.show', $validated['case_id'])
            ->with('success', 'تم حفظ الموعد بنجاح');
    }

    public function show(Appointment $appointment)
    {
        $appointments = Appointment::all();
        return view('appointments.show', compact('appointments'));
    }

    public function edit(Appointment $appointment)
    {
        return view('appointments.edit', compact('appointment'));
    }

    public function update(Request $request, Appointment $appointment)
    {
        $validated = $request->validate([
            'case_id' => 'required|exists:cases,id',
            'date'    => 'required|date',
            'time'    => 'required|date_format:H:i',
            'notes'   => 'nullable|string',
        ]);

        $appointment->update([
            'case_id' => $validated['case_id'],
            'date'    => $validated['date'],
            'time'    => $validated['time'],
            'notes'   => $validated['notes'] ?? '',
        ]);

        // 🔔 Notify all lawyers linked to this case
        $appointment->load('case');
        $this->notifyLawyersOnCase($appointment, 'updated');

        return redirect()->back()->with('success', 'تم تحديث الموعد بنجاح');
    }

    /**
     * Fire CaseSessionUpdatedNotification to all lawyers linked to the appointment's case.
     */
    private function notifyLawyersOnCase(Appointment $appointment, string $action): void
    {
        $case = $appointment->case;
        if (! $case) {
            return;
        }

        // Notify via lawyerscases pivot (all assigned lawyers)
        $case->load('lawyers');
        foreach ($case->lawyers as $lawyer) {
            $user = User::where('lawyer_id', $lawyer->id)->first();
            if ($user) {
                $user->notify(new CaseSessionUpdatedNotification($appointment, $action));
            }
        }

        // Also notify the lead lawyer stored on the case itself
        if ($case->lawyer_id) {
            $leadUser = User::where('lawyer_id', $case->lawyer_id)->first();
            if ($leadUser && ! $case->lawyers->pluck('id')->contains($case->lawyer_id)) {
                $leadUser->notify(new CaseSessionUpdatedNotification($appointment, $action));
            }
        }
    }

    public function destroy($id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->delete();

        return redirect()->route('appointments.index')
            ->with('success', 'تم حذف الموعد بنجاح');
    }
}