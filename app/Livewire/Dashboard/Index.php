<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use Carbon\Carbon;
use App\Models\LegalCase;
use App\Models\Client;
use App\Models\Appointment;
use App\Models\CaseExpense;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class Index extends Component
{
    private function getStatusColor($status): string
    {
        $status = trim((string) $status);
        $colors = [
            'مفتوحة'       => 'success',
            'متداولة'      => 'primary',
            'مؤجلة'        => 'warning',
            'محجوزة للحكم' => 'info',
            'منتهية'       => 'success',
            'مستأنفة'      => 'danger',
            'محفوظة'       => 'secondary',
            'معلقة'        => 'warning',
            'Open'                  => 'success',
            'In Progress'           => 'primary',
            'Postponed'             => 'warning',
            'Reserved for Judgment' => 'info',
            'Closed'                => 'success',
            'Appealed'              => 'danger',
            'Archived'              => 'secondary',
            'Pending'               => 'warning',
        ];
        return $colors[$status] ?? 'primary';
    }

    public function approveExpense(int $expenseId): void
    {
        abort_unless(auth()->user()?->isAdmin(), 403);
        CaseExpense::findOrFail($expenseId)->update([
            'status'      => 'approved',
            'approved_by' => auth()->id(),
        ]);
        session()->flash('expense_flash', __('Expense approved.'));
    }

    public function rejectExpense(int $expenseId): void
    {
        abort_unless(auth()->user()?->isAdmin(), 403);
        CaseExpense::findOrFail($expenseId)->update([
            'status'      => 'rejected',
            'approved_by' => auth()->id(),
        ]);
        session()->flash('expense_flash', __('Expense rejected.'));
    }

    public function render()
    {
        $user    = auth()->user();
        $today   = Carbon::today();
        $isAdmin = $user?->isAdmin();
        $locale  = app()->getLocale();
        $demoCountry = session('demo_country');
        $isEnglish = $locale === 'en' || in_array($demoCountry, ['US', 'GB']);

        $casesQuery        = LegalCase::query();
        $appointmentsQuery = Appointment::query();
        $clientsQuery      = Client::query();

        if (session('is_demo')) {
            if ($isEnglish) {
                $casesQuery->where(function($q) {
                    $q->whereHas('lawyers', function($sq) {
                        $sq->where('address', 'like', '%USA%')->orWhere('address', 'like', '%UK%');
                    })
                    ->orWhere('case_number', 'like', 'CASE-US-%')
                    ->orWhere('case_number', 'like', 'CASE-GB-%');
                });
                $appointmentsQuery->whereHas('case', function($q) {
                    $q->whereHas('lawyers', function($sq) {
                        $sq->where('address', 'like', '%USA%')->orWhere('address', 'like', '%UK%');
                    })
                    ->orWhere('case_number', 'like', 'CASE-US-%')
                    ->orWhere('case_number', 'like', 'CASE-GB-%');
                });
                $clientsQuery->where(function($q) {
                    $q->where('address', 'like', '%USA%')->orWhere('address', 'like', '%UK%');
                });
            } else {
                $casesQuery->where(function($q) {
                    $q->whereDoesntHave('lawyers', function($sq) {
                        $sq->where('address', 'like', '%USA%')->orWhere('address', 'like', '%UK%');
                    })
                    ->where('case_number', 'not like', 'CASE-US-%')
                    ->where('case_number', 'not like', 'CASE-GB-%');
                });
                $appointmentsQuery->whereHas('case', function($q) {
                    $q->whereDoesntHave('lawyers', function($sq) {
                        $sq->where('address', 'like', '%USA%')->orWhere('address', 'like', '%UK%');
                    })
                    ->where('case_number', 'not like', 'CASE-US-%')
                    ->where('case_number', 'not like', 'CASE-GB-%');
                });
                $clientsQuery->where(function($q) {
                    $q->where('address', 'not like', '%USA%')->where('address', 'not like', '%UK%');
                });
            }
        }

        if ($user && $user->isLawyer()) {
            $lawyerId = $user->lawyer_id;
            $casesQuery->where('lawyer_id', $lawyerId);
            $appointmentsQuery->whereHas('case', function ($q) use ($lawyerId) {
                $q->where('lawyer_id', $lawyerId);
            });
            $clientsQuery->whereHas('cases', function ($q) use ($lawyerId) {
                $q->where('lawyer_id', $lawyerId);
            });
        }

        // Card stats (available to all roles)
        $activeCasesCount   = (clone $casesQuery)->whereNotIn('status', ['منتهية', 'محفوظة', 'Closed', 'Archived'])->count();
        $todaySessionsCount = (clone $appointmentsQuery)->whereDate('date', $today)->count();
        $totalClientsCount  = (clone $clientsQuery)->count();
        $urgentTasksCount   = (clone $appointmentsQuery)
                                ->whereDate('date', '>=', $today)
                                ->whereDate('date', '<=', $today->copy()->addDays(3))
                                ->count();

        // ── Financial stats: ADMIN ONLY ──────────────────────────────────
        $totalFees      = null;
        $totalPaid      = null;
        $collectionRate = null;

        if ($isAdmin) {
            $feesData = (clone $casesQuery)->selectRaw('
                            SUM(total_costs)              as total_fees,
                            SUM(deposit)                  as total_paid,
                            SUM(total_costs - deposit)    as total_remaining
                        ')->first();

            $totalFees      = $feesData->total_fees    ?? 0;
            $totalPaid      = $feesData->total_paid    ?? 0;
            $collectionRate = $totalFees > 0
                                ? round(($totalPaid / $totalFees) * 100, 1)
                                : 0;
        }

        // ── Pending Expense Claims widget (Admin only) ────────────────────
        $pendingExpenses   = collect();
        $lawyerLeaderboard = collect();

        if ($isAdmin) {
            $pendingExpenses = CaseExpense::where('status', 'pending')
                ->select(['id', 'case_id', 'amount', 'category', 'user_id', 'notes', 'status', 'created_at'])
                ->with([
                    'case:id,case_number',
                    'submittedBy:id,name',
                ])
                ->latest()
                ->take(10)
                ->get();

            // ── Lawyer Performance Leaderboard (Admin only) ──────────────────────
            $leaderboardQuery = \App\Models\Lawyer::query()
                ->select('lawyers.id', 'lawyers.name')
                ->selectRaw('
                    COUNT(DISTINCT CASE WHEN cases.status NOT IN (?, ?, ?, ?) THEN cases.id END)  as active_cases,
                    COUNT(DISTINCT CASE WHEN cases.status IN (?, ?)     THEN cases.id END)  as won_cases,
                    COUNT(DISTINCT client_case.client_id)                                   as total_clients,
                    COUNT(DISTINCT CASE WHEN client_payments.status = ? THEN client_payments.id END) as confirmed_payments
                ', ['منتهية', 'محفوظة', 'Closed', 'Archived', 'منتهية', 'Closed', 'confirmed'])
                ->selectRaw('
                    (COUNT(DISTINCT CASE WHEN cases.status NOT IN (?, ?, ?, ?) THEN cases.id END) * 3)
                  + (COUNT(DISTINCT CASE WHEN cases.status IN (?, ?)     THEN cases.id END) * 5)
                  + (COUNT(DISTINCT client_case.client_id) * 2)
                  + (COUNT(DISTINCT CASE WHEN client_payments.status = ? THEN client_payments.id END) * 1)
                    as performance_score
                ', ['منتهية', 'محفوظة', 'Closed', 'Archived', 'منتهية', 'Closed', 'confirmed'])
                ->leftJoin('lawyerscases',     'lawyers.id', '=', 'lawyerscases.lawyer_id')
                ->leftJoin('cases',            'lawyerscases.case_id', '=', 'cases.id')
                ->leftJoin('client_case',      'cases.id', '=', 'client_case.case_id')
                ->leftJoin('client_payments',  'cases.id', '=', 'client_payments.case_id')
                ->groupBy('lawyers.id', 'lawyers.name')
                ->orderByDesc('performance_score');

            if (session('is_demo')) {
                if ($isEnglish) {
                    $leaderboardQuery->where(function($q) {
                        $q->where('lawyers.address', 'like', '%USA%')->orWhere('lawyers.address', 'like', '%UK%');
                    });
                } else {
                    $leaderboardQuery->where('lawyers.address', 'not like', '%USA%')->where('lawyers.address', 'not like', '%UK%');
                }
            }
            
            $lawyerLeaderboard = $leaderboardQuery->take(5)->get();
        }

        // Charts data
        $currentYear = date('Y');
        $monthSelect = DB::connection()->getDriverName() === 'sqlite'
            ? DB::raw("cast(strftime('%m', created_at) as integer) as month")
            : DB::raw('MONTH(created_at) as month');

        $casesPerMonth = (clone $casesQuery)
            ->select($monthSelect, DB::raw('COUNT(*) as count'))
            ->whereYear('created_at', $currentYear)
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();

        // Locale-aware month labels
        if ($isEnglish) {
            $monthsLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        } else {
            $monthsLabels = ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو', 'يوليو', 'أغسطس', 'سبتمبر', 'أكتوبر', 'نوفمبر', 'ديسمبر'];
        }

        $chartCasesCount = [];
        for ($i = 1; $i <= 12; $i++) {
            $chartCasesCount[] = $casesPerMonth[$i] ?? 0;
        }

        $statusCounts = (clone $casesQuery)
            ->select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // Translate status labels for chart when in English mode
        $chartStatusLabels = [];
        $chartStatusData   = [];
        foreach ($statusCounts as $statusKey => $count) {
            $chartStatusLabels[] = $isEnglish ? __($statusKey) : $statusKey;
            $chartStatusData[]   = $count;
        }

        $recentCases = (clone $casesQuery)
            ->select(['id', 'case_number', 'rival_name', 'status', 'created_at'])
            ->with(['clients:id,name'])
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($case) use ($isEnglish) {
                $case->display_client_name = $case->clients?->first()?->name ?? ($isEnglish ? 'Unassigned' : 'غير محدد');
                $case->opponent_name       = $case->rival_name ?? ($isEnglish ? 'N/A' : 'غير محدد');
                $case->status_name         = $isEnglish ? __($case->status) : $case->status;
                $case->status_color        = $this->getStatusColor($case->status);
                return $case;
            });

        $todayAgenda = (clone $appointmentsQuery)
            ->select(['id', 'case_id', 'date', 'time', 'notes'])
            ->with(['case:id,case_number'])
            ->whereDate('date', $today)
            ->orderBy('time', 'asc')
            ->get()
            ->map(function ($appt) use ($isEnglish) {
                $appt->display_title = $appt->case?->case_number
                    ? ($isEnglish ? 'Case No.: ' : 'قضية رقم: ') . $appt->case->case_number
                    : ($isEnglish ? 'General Appointment' : 'موعد عام');
                $appt->display_desc = $appt->notes ?? ($isEnglish ? 'No additional details' : 'لا يوجد تفاصيل إضافية');
                $appt->color_hex    = '#d4af37';
                return $appt;
            });

        $calendarEvents = (clone $appointmentsQuery)
            ->select(['id', 'case_id', 'date', 'time', 'notes'])
            ->with(['case:id,case_number', 'case.clients:id,name'])
            ->get()
            ->map(function ($appt) use ($isEnglish) {
                return [
                    'date'        => Carbon::parse($appt->date)->format('Y-m-d'),
                    'time'        => $appt->time ? Carbon::parse($appt->time)->format('h:i A') : ($isEnglish ? 'TBD' : 'غير محدد'),
                    'title'       => $appt->case?->case_number
                        ? ($isEnglish ? 'Case No.: ' : 'قضية رقم: ') . $appt->case->case_number
                        : ($isEnglish ? 'General Appointment' : 'موعد عام'),
                    'notes'       => $appt->notes,
                    'client_name' => $appt->case?->clients->first()?->name ?? ($isEnglish ? 'Unassigned' : 'غير محدد'),
                ];
            })->toArray();

        return view('livewire.dashboard.index', compact(
            'activeCasesCount', 'todaySessionsCount', 'totalClientsCount', 'urgentTasksCount',
            'totalFees', 'totalPaid', 'collectionRate',
            'pendingExpenses', 'lawyerLeaderboard', 'isAdmin',
            'recentCases', 'todayAgenda',
            'monthsLabels', 'chartCasesCount', 'chartStatusLabels', 'chartStatusData', 'calendarEvents',
            'isEnglish'
        ))->title(($isEnglish ? 'Dashboard' : 'الرئيسية') . ' | ' . firm_name());
    }
}
