<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\LegalCase;
use App\Models\Client;
use App\Models\Appointment;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user  = auth()->user();
        $today = Carbon::today();

        $casesQuery        = LegalCase::query();
        $appointmentsQuery = Appointment::query();
        $clientsQuery      = Client::query();

        if ($user->role === 'lawyer') {
            $lawyerId = $user->lawyer_id;
            $casesQuery->where('lawyer_id', $lawyerId);
            $appointmentsQuery->whereHas('case', function ($q) use ($lawyerId) {
                $q->where('lawyer_id', $lawyerId);
            });
            $clientsQuery->whereHas('cases', function ($q) use ($lawyerId) {
                $q->where('lawyer_id', $lawyerId);
            });
        }

        // إحصائيات الـ Cards
        $activeCasesCount   = (clone $casesQuery)->whereNotIn('status', ['منتهية', 'محفوظة'])->count();
        $todaySessionsCount = (clone $appointmentsQuery)->whereDate('date', $today)->count();
        $totalClientsCount  = (clone $clientsQuery)->count();
        $urgentTasksCount   = (clone $appointmentsQuery)
                                ->whereDate('date', '>', $today)
                                ->whereDate('date', '<=', $today->copy()->addDays(3))
                                ->count();

        // إحصائيات الأتعاب
        $feesData        = (clone $casesQuery)->selectRaw('
                                SUM(total_costs)              as total_fees,
                                SUM(deposit)                  as total_paid,
                                SUM(total_costs - deposit)    as total_remaining
                            ')->first();

        $totalFees       = $feesData->total_fees    ?? 0;
        $totalPaid       = $feesData->total_paid    ?? 0;

        $collectionRate  = $totalFees > 0
                            ? round(($totalPaid / $totalFees) * 100, 1)
                            : 0;

        // الرسم البياني الخطي
        $currentYear = date('Y');
        $casesPerMonth = (clone $casesQuery)
            ->select(DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(*) as count'))
            ->whereYear('created_at', $currentYear)
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();

        $monthsLabels    = ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو', 'يوليو', 'أغسطس', 'سبتمبر', 'أكتوبر', 'نوفمبر', 'ديسمبر'];
        $chartCasesCount = [];
        for ($i = 1; $i <= 12; $i++) {
            $chartCasesCount[] = $casesPerMonth[$i] ?? 0;
        }

        // الرسم البياني الدائري
        $statusCounts = (clone $casesQuery)
            ->select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $chartStatusLabels = array_keys($statusCounts);
        $chartStatusData   = array_values($statusCounts);

        // الجداول
        $recentCases = (clone $casesQuery)
            ->with('clients')
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($case) {
                $case->display_client_name = $case->clients?->first()?->name ?? 'غير محدد';
                $case->opponent_name       = $case->rival_name ?? 'غير محدد';
                $case->status_name         = $case->status; // الاسم الموحد
                $case->status_color        = $this->getStatusColor($case->status);
                return $case;
            });

        $todayAgenda = (clone $appointmentsQuery)
            ->with('case')
            ->whereDate('date', $today)
            ->orderBy('time', 'asc')
            ->get()
            ->map(function ($appt) {
                $appt->display_title = $appt->case?->case_number
                    ? 'قضية رقم: ' . $appt->case->case_number
                    : 'موعد عام';
                $appt->display_desc = $appt->notes ?? 'لا يوجد تفاصيل إضافية';
                $appt->color_hex    = '#d4af37';
                return $appt;
            });

        // تجهيز بيانات التقويم
        $calendarEvents = (clone $appointmentsQuery)
            ->with('case')
            ->get()
            ->map(function ($appt) {
                return [
                    'date'  => \Carbon\Carbon::parse($appt->date)->format('Y-m-d'),
                    'time'  => $appt->time ? \Carbon\Carbon::parse($appt->time)->format('h:i A') : 'غير محدد',
                    'title' => $appt->case?->case_number
                        ? 'قضية رقم: ' . $appt->case->case_number
                        : ($appt->title ?? 'موعد عام'),
                    'notes' => $appt->notes
                ];
            })->toArray();

        return view('home', compact(
            'activeCasesCount', 'todaySessionsCount', 'totalClientsCount', 'urgentTasksCount',
            'totalFees', 'totalPaid', 'collectionRate', 'recentCases', 'todayAgenda',
            'monthsLabels', 'chartCasesCount', 'chartStatusLabels', 'chartStatusData', 'calendarEvents'
        ));
    }

    private function getStatusColor($status)
{
    // بنظف الحالة من أي مسافات زائدة عشان نضمن التطابق
    $status = trim($status); 
    
    $colors = [
        'مفتوحة'       => 'success',   // أخضر
        'متداولة'      => 'primary',   // أزرق
        'مؤجلة'        => 'warning',   // أصفر
        'محجوزة للحكم' => 'info',      // لبني
        'منتهية'       => 'success',   // أخضر
        'مستأنفة'      => 'danger',    // أحمر
        'محفوظة'       => 'secondary', // رمادي
        'معلقة'        => 'warning',   // أصفر
    ];

    // لو الحالة مش موجودة في القائمة، هيرجع 'primary' (أزرق) بدل الكحلي (secondary)
    return $colors[$status] ?? 'primary'; 
}
}