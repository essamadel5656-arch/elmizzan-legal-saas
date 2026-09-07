<?php

namespace App\Livewire\ClientPortal;

use Livewire\Component;
use App\Models\LegalCase;
use App\Models\ClientPayment;
use App\Models\DocumentRequest;
use Carbon\Carbon;

class Dashboard extends Component
{
    public function render()
    {
        $user   = auth()->user();
        $client = $user->client;

        abort_unless($user->isClient() && $client, 403);

        // Cases this client is linked to (read-only, no internal notes/expenses)
        $cases = $client->cases()
            ->with(['court', 'appointments' => function ($q) {
                $q->where('date', '>=', now()->toDateString())->orderBy('date');
            }])
            ->get()
            ->map(function ($case) {
                return [
                    'id'           => $case->id,
                    'case_number'  => $case->case_number,
                    'status'       => $case->status,
                    'court'        => $case->court?->name ?? 'غير محدد',
                    'next_hearing' => $case->appointments->first()?->date
                        ? Carbon::parse($case->appointments->first()->date)->locale('ar')->translatedFormat('l، j F Y')
                        : null,
                    // Client sees only their agreed fee balance — NOT total firm revenue
                    'agreed_fee'   => $case->agreed_legal_fee,
                    'paid'         => $case->deposit ?? 0,
                    'remaining'    => ($case->agreed_legal_fee ?? 0) - ($case->deposit ?? 0),
                ];
            });

        // Upcoming hearings across all client cases
        $upcomingHearings = $client->cases()
            ->with(['appointments' => function ($q) {
                $q->where('date', '>=', now()->toDateString())->orderBy('date');
            }])
            ->get()
            ->flatMap(fn($c) => $c->appointments->map(fn($a) => [
                'case_number' => $c->case_number,
                'date'        => Carbon::parse($a->date)->locale('ar')->translatedFormat('l، j F Y'),
                'time'        => $a->time ? Carbon::parse($a->time)->format('h:i A') : null,
                'notes'       => $a->notes,
            ]))
            ->sortBy('date')
            ->take(5)
            ->values();

        // Pending document requests for this client
        $pendingDocRequests = DocumentRequest::where('client_id', $client->id)
            ->where('status', 'pending')
            ->with('case')
            ->latest()
            ->get();

        // Payment history (confirmed only)
        $payments = ClientPayment::where('client_id', $client->id)
            ->with('case')
            ->latest()
            ->get();

        return view('livewire.client-portal.dashboard', compact(
            'client', 'cases', 'upcomingHearings', 'pendingDocRequests', 'payments'
        ))->title('بوابة الموكل | ' . firm_name());
    }
}
