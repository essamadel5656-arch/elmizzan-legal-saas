<?php

namespace App\Livewire\Demo;

use Livewire\Component;
use App\Models\User;
use App\Models\LegalCase;
use App\Models\Client;
use App\Models\Lawyer;
use App\Models\Court;
use App\Models\Jurisdiction;
use App\Models\CourtLevel;
use App\Models\Appointment;
use App\Models\Setting;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;

class DemoAccess extends Component
{
    public bool $loading = false;

    public function enterDemo(string $countryCode = 'EG'): void
    {
        $this->loading = true;

        $currencyMap = [
            'EG' => ['currency' => 'ج.م.'],
            'SA' => ['currency' => 'ر.س.'],
            'AE' => ['currency' => 'د.إ.'],
            'OM' => ['currency' => 'ر.ع.'],
            'US' => ['currency' => '$'],
            'GB' => ['currency' => '£'],
        ];

        $demoCurrency = $currencyMap[$countryCode]['currency'] ?? 'ج.م.';

        // Auto-set locale based on country
        $localeMap = [
            'EG' => 'ar', 'SA' => 'ar', 'AE' => 'ar', 'OM' => 'ar',
            'US' => 'en', 'GB' => 'en',
        ];
        $locale = $localeMap[$countryCode] ?? 'ar';
        session(['app_locale' => $locale]);

        // Find or create the shared demo account
        $demoEmail = 'demo@' . Str::slug(firm_name()) . '.demo';
        $demoUser  = User::firstOrCreate(
            ['email' => $demoEmail],
            [
                'name'     => $locale === 'en' ? 'Demo User — ' . firm_name() : 'مستخدم تجريبي — ' . firm_name(),
                'password' => Hash::make(Str::random(32)),
                'role'     => 'admin',
            ]
        );

        // Seed demo data if none exists
        $this->seedDemoData($demoUser);

        // Log in as the demo user
        Auth::login($demoUser);

        // Flag session as demo mode
        session([
            'is_demo'       => true,
            'demo_user_id'  => $demoUser->id,
            'demo_currency' => $demoCurrency,
            'demo_country'  => $countryCode,
        ]);

        $this->redirect('/home', navigate: true);
    }

    private function seedDemoData(User $demoUser): void
    {
        if (LegalCase::count() > 0) return;

        Setting::set('app_name', 'الميزان');
        Setting::set('ai_feature_enabled', '1');

        $jurisdiction = Jurisdiction::firstOrCreate(['name' => 'القضاء العادي']);
        $level        = CourtLevel::firstOrCreate(['name' => 'ابتدائي']);
        $court        = Court::firstOrCreate(
            ['name' => 'محكمة القاهرة الابتدائية'],
            ['jurisdiction_id' => $jurisdiction->id]
        );

        $lawyer = Lawyer::firstOrCreate(
            ['email' => 'demo.lawyer@example.com'],
            [
                'name'           => 'أ/ سامي القانوني',
                'phone'          => '01012345678',
                'specialization' => 'قانون مدني وتجاري',
                'license_number' => 'DM-001',
                'address'        => 'القاهرة، مصر',
                'degree'         => 'استئناف',
                'bio'            => 'محامٍ بالاستئناف العالي خبرة 15 عامًا في القانون المدني والتجاري.',
            ]
        );

        $clients = [];
        $clientsData = [
            ['name' => 'محمد إبراهيم السيد', 'phone' => '01099991111', 'email' => 'client1@demo.com', 'nid' => '29001010123456', 'address' => 'القاهرة'],
            ['name' => 'فاطمة علي حسن',      'phone' => '01088882222', 'email' => 'client2@demo.com', 'nid' => '29502020234567', 'address' => 'الجيزة'],
            ['name' => 'أحمد خالد منصور',    'phone' => '01077773333', 'email' => 'client3@demo.com', 'nid' => '28803030345678', 'address' => 'الإسكندرية'],
        ];
        foreach ($clientsData as $cd) {
            $clients[] = Client::firstOrCreate(['email' => $cd['email']], $cd);
        }

        $casesData = [
            ['case_number' => 'DEMO-2024-001', 'status' => 'مفتوحة',    'rival_name' => 'شركة التطوير العقاري',     'total_costs' => 15000, 'deposit' => 8000,  'agreed_legal_fee' => 15000],
            ['case_number' => 'DEMO-2024-002', 'status' => 'متداولة',   'rival_name' => 'مؤسسة الإبداع التجاري',    'total_costs' => 25000, 'deposit' => 12000, 'agreed_legal_fee' => 25000],
            ['case_number' => 'DEMO-2024-003', 'status' => 'محجوزة للحكم','rival_name'=> 'ورثة السيد عمر النجار',   'total_costs' => 10000, 'deposit' => 10000, 'agreed_legal_fee' => 10000],
            ['case_number' => 'DEMO-2024-004', 'status' => 'مؤجلة',     'rival_name' => 'بنك الاستثمار الوطني',     'total_costs' => 40000, 'deposit' => 20000, 'agreed_legal_fee' => 40000],
            ['case_number' => 'DEMO-2024-005', 'status' => 'منتهية',    'rival_name' => 'وزارة الموارد البشرية',     'total_costs' => 8000,  'deposit' => 8000,  'agreed_legal_fee' => 8000],
        ];

        foreach ($casesData as $i => $cd) {
            $client = $clients[$i % count($clients)];
            $case   = LegalCase::firstOrCreate(
                ['case_number' => $cd['case_number']],
                array_merge($cd, [
                    'court_id'        => $court->id,
                    'jurisdiction_id' => $jurisdiction->id,
                    'court_level_id'  => $level->id,
                    'lawyer_id'       => $lawyer->id,
                    'rival_number'    => '010' . rand(10000000, 99999999),
                    'rival_address'   => 'القاهرة، مصر',
                    'rival_nid'       => (string)rand(10000000000000, 99999999999999),
                    'description'     => 'قضية تجريبية للعرض — ' . $cd['case_number'],
                ])
            );

            $case->clients()->syncWithoutDetaching([$client->id]);
            $case->lawyers()->syncWithoutDetaching([$lawyer->id => ['role' => 'lead']]);

            Appointment::firstOrCreate(
                ['case_id' => $case->id, 'date' => Carbon::now()->addDays($i + 1)->toDateString()],
                ['time' => '10:00', 'notes' => 'جلسة استماع — ' . $cd['case_number']]
            );
        }
    }

    public function render()
    {
        return view('livewire.demo.demo-access')
            ->title(__('Interactive Demo Showcase') . ' | ' . firm_name());
    }
}
