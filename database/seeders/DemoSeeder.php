<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\LegalCase;
use App\Models\Client;
use App\Models\Lawyer;
use App\Models\Court;
use App\Models\Jurisdiction;
use App\Models\CourtLevel;
use App\Models\Appointment;
use App\Models\Setting;
use App\Models\CaseExpense;
use App\Models\ClientPayment;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\WorkspaceMessage;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        Setting::set('app_name', 'الميزان');
        Setting::set('ai_feature_enabled', '1');
        Setting::set('bank_name', 'بنك مسقط');
        Setting::set('bank_iban', 'OM1234567890123456789012');
        Setting::set('instapay_address', 'mizzan@instapay');
        Setting::set('mobile_wallet', '+96891234567');

        // Create Demo Admin User
        $admin = User::firstOrCreate(
            ['email' => 'demo-admin@elmizzan.com'],
            [
                'name'     => 'Demo Admin — El-Mizzan',
                'password' => Hash::make('demo123456'),
                'role'     => 'admin',
            ]
        );

        $regions = [
            'EG' => [
                'courts'        => ['محكمة جنوب القاهرة الابتدائية', 'محكمة استئناف القاهرة', 'مجلس الدولة', 'محكمة النقض'],
                'jurisdictions' => ['النيابة العامة', 'القضاء الإداري'],
                'lawyers'       => ['أ/ أحمد محمود', 'أ/ محمد إبراهيم'],
                'clients'       => ['محمود حسن', 'فاطمة علي'],
                'address'       => 'القاهرة، مصر',
                'rival_names'   => ['شركة النيل للتطوير', 'مؤسسة الإبداع التجاري', 'بنك الاستثمار الوطني'],
                'descriptions'  => ['نزاع عقاري', 'قضية تجارية', 'مطالبة مالية'],
                'locale'        => 'ar',
            ],
            'SA' => [
                'courts'        => ['المحكمة العامة بالرياض', 'المحكمة التجارية', 'المحكمة العمالية', 'محكمة التنفيذ'],
                'jurisdictions' => ['ديوان المظالم', 'النيابة العامة'],
                'lawyers'       => ['أ/ عبد الله بن سعد', 'أ/ فهد الشمري'],
                'clients'       => ['خالد بن فهد', 'نورة العتيبي'],
                'address'       => 'الرياض، السعودية',
                'rival_names'   => ['شركة الخليج للتجارة', 'مؤسسة الرياض', 'بنك الراجحي'],
                'descriptions'  => ['نزاع تجاري', 'قضية عمالية', 'مطالبة مالية'],
                'locale'        => 'ar',
            ],
            'AE' => [
                'courts'        => ['محاكم دبي الابتدائية', 'محكمة استئناف أبوظبي الاتحادية', 'محكمة التمييز'],
                'jurisdictions' => ['النيابة العامة الاتحادية', 'لجان فض المنازعات الإيجارية'],
                'lawyers'       => ['أ/ سعيد بن مكتوم', 'أ/ ماجد المرزوقي'],
                'clients'       => ['سلطان الفلاسي', 'مريم الكتبي'],
                'address'       => 'دبي، الإمارات',
                'rival_names'   => ['شركة الإمارات للاستثمار', 'مجموعة عبر للتطوير'],
                'descriptions'  => ['نزاع عقاري', 'قضية تجارية'],
                'locale'        => 'ar',
            ],
            'OM' => [
                'courts'        => ['المحكمة الابتدائية بمسقط', 'محكمة الاستئناف بالسيب', 'المحكمة العليا'],
                'jurisdictions' => ['الادعاء العام', 'لجان التوفيق والمصالحة'],
                'lawyers'       => ['أ/ ناصر بن سعيد الشامسي', 'أ/ هلال البوسعيدي'],
                'clients'       => ['أحمد بن حمد البوسعيدي', 'شركة الأفق للتجارة'],
                'address'       => 'مسقط، عُمان',
                'rival_names'   => ['شركة نفط عُمان', 'مؤسسة السلطنة التجارية'],
                'descriptions'  => ['نزاع تجاري', 'قضية عقارية'],
                'locale'        => 'ar',
            ],
            'US' => [
                'courts'        => ['U.S. District Court (S.D.N.Y.)', 'Delaware Court of Chancery', 'U.S. Court of Appeals (2nd Cir.)', 'Federal Bankruptcy Court'],
                'jurisdictions' => ['Federal Commercial Division', 'Chancery Litigation', 'Appellate Division', 'Federal Bankruptcy Jurisdiction'],
                'lawyers'       => ['Jonathan D. Harrington', 'Sophia L. Whitfield'],
                'clients'       => ['Alexander Wright', 'Nexus Holdings LLC'],
                'address'       => 'New York, NY, USA',
                'rival_names'   => ['Global Vanguard Inc.', 'Pinnacle Capital Group', 'Horizon Enterprises LLC'],
                'descriptions'  => ['Commercial contract dispute', 'Securities litigation', 'Corporate acquisition dispute', 'Breach of fiduciary duty claim'],
                'locale'        => 'en',
            ],
            'GB' => [
                'courts'        => ['High Court of Justice (Commercial Court)', 'Crown Court', 'Court of Appeal (Civil Division)', 'Employment Appeal Tribunal'],
                'jurisdictions' => ['Commercial Chancery Division', 'Employment Tribunal Jurisdiction', 'Civil Appellate Division'],
                'lawyers'       => ['Oliver T. Pemberton', 'Amelia K. Thornton'],
                'clients'       => ['Sophia Sterling', 'Apex Logistics Ltd'],
                'address'       => 'London, England, UK',
                'rival_names'   => ['Whitmore & Associates Ltd.', 'Crown Estate Holdings', 'Thornfield Capital PLC'],
                'descriptions'  => ['Commercial lease dispute', 'Employment wrongful termination', 'Intellectual property infringement', 'Corporate merger litigation'],
                'locale'        => 'en',
            ],
        ];

        $level = CourtLevel::firstOrCreate(['name' => 'ابتدائية']);

        $allLawyers       = [];
        $allClients       = [];
        $allCourts        = [];
        $allJurisdictions = [];

        // Map regions to country codes for filtering later
        $regionCountryMap = [];

        foreach ($regions as $code => $data) {
            foreach ($data['jurisdictions'] as $jName) {
                $jurisdiction = Jurisdiction::firstOrCreate(['name' => $jName]);
                $allJurisdictions[$code][] = $jurisdiction;
                foreach ($data['courts'] as $cName) {
                    $court = Court::firstOrCreate(
                        ['name' => $cName],
                        ['jurisdiction_id' => $jurisdiction->id]
                    );
                    $allCourts[$code][] = $court;
                }
            }

            foreach ($data['lawyers'] as $index => $lName) {
                $isEnglish = $data['locale'] === 'en';
                $lawyer = Lawyer::firstOrCreate(
                    ['email' => 'lawyer' . $code . $index . '@elmizzan.com'],
                    [
                        'name'           => $lName,
                        'phone'          => '900000' . rand(10, 99),
                        'specialization' => $isEnglish ? 'Commercial & Corporate Litigation' : 'قضايا تجارية وعمالية',
                        'license_number' => $code . '-' . rand(1000, 9999),
                        'address'        => $data['address'],
                        'degree'         => $isEnglish ? 'Senior Partner' : 'استئناف',
                        'bio'            => $isEnglish
                            ? 'Senior partner with over 15 years of experience in commercial and corporate litigation.'
                            : 'محامٍ ومستشار قانوني بخبرة تتجاوز 15 عاماً.',
                    ]
                );

                $lawyerUser = User::firstOrCreate(
                    ['email' => 'lawyer' . $code . $index . '@elmizzan.com'],
                    [
                        'name'      => $lName,
                        'password'  => Hash::make('password'),
                        'role'      => 'lawyer',
                        'lawyer_id' => $lawyer->id,
                    ]
                );
                $allLawyers[$code][] = ['lawyer' => $lawyer, 'user' => $lawyerUser];
            }

            foreach ($data['clients'] as $index => $cName) {
                $client = Client::firstOrCreate(
                    ['email' => 'client' . $code . $index . '@example.com'],
                    [
                        'name'    => $cName,
                        'phone'   => '900000' . rand(10, 99),
                        'nid'     => (string)rand(1000000000, 9999999999),
                        'address' => $data['address'],
                    ]
                );
                $clientUser = User::firstOrCreate(
                    ['email' => 'client' . $code . $index . '@example.com'],
                    [
                        'name'      => $cName,
                        'password'  => Hash::make('password'),
                        'role'      => 'client',
                        'client_id' => $client->id,
                    ]
                );
                $allClients[$code][] = $client;
            }
        }

        // Flatten for workspace messages
        $flatLawyers = [];
        foreach ($allLawyers as $lawyers) {
            foreach ($lawyers as $l) {
                $flatLawyers[] = $l;
            }
        }

        // Demo Workspace Messages
        $mainLawyerUser = $flatLawyers[0]['user'];
        $admin->update(['last_seen_at' => now()]);
        $mainLawyerUser->update(['last_seen_at' => now()->subMinutes(rand(1, 4))]);

        $demoMessages = [
            ['from' => $admin,          'to' => $mainLawyerUser, 'body' => 'Good morning — has the case file been reviewed?',           'ago' => 120],
            ['from' => $mainLawyerUser, 'to' => $admin,          'body' => 'Good morning. Yes, I reviewed the file and will share notes today.', 'ago' => 115],
            ['from' => $admin,          'to' => null,             'body' => '📢 Notice: System maintenance tonight 2–4 AM. Please save your work.', 'ago' => 200],
            ['from' => $mainLawyerUser, 'to' => $admin,          'body' => 'Could you review the fee account for the client?',           'ago' => 10],
        ];

        foreach ($demoMessages as $msg) {
            $isRead = $msg['ago'] > 15;
            WorkspaceMessage::firstOrCreate(
                [
                    'sender_id'   => $msg['from']->id,
                    'receiver_id' => $msg['to']?->id,
                    'body'        => $msg['body'],
                ],
                [
                    'read_at'    => $isRead ? now()->subMinutes($msg['ago'] - 2) : null,
                    'created_at' => now()->subMinutes($msg['ago']),
                    'updated_at' => now()->subMinutes($msg['ago']),
                ]
            );
        }

        $arStatuses = ['مفتوحة', 'متداولة', 'مؤجلة', 'محجوزة للحكم', 'منتهية', 'مستأنفة'];
        $enStatuses = ['Open', 'In Progress', 'Postponed', 'Reserved for Judgment', 'Closed', 'Appealed'];

        $caseCounter = 1;
        foreach ($regions as $code => $data) {
            $regionCourts  = $allCourts[$code] ?? [];
            $regionLawyers = $allLawyers[$code] ?? [];
            $regionClients = $allClients[$code] ?? [];

            if (empty($regionCourts) || empty($regionLawyers) || empty($regionClients)) {
                continue;
            }

            $casesForRegion = ($code === 'US' || $code === 'GB') ? 8 : 5;

            for ($i = 1; $i <= $casesForRegion; $i++) {
                $randomDays = rand(0, 180);
                $createdAt  = Carbon::now()->subDays($randomDays);

                $court      = $regionCourts[array_rand($regionCourts)];
                $lawyerData = $regionLawyers[array_rand($regionLawyers)];
                $lawyer     = $lawyerData['lawyer'];
                $lawyerUser = $lawyerData['user'];
                $client     = $regionClients[array_rand($regionClients)];

                $rivalName   = $data['rival_names'][array_rand($data['rival_names'])];
                $description = $data['descriptions'][array_rand($data['descriptions'])];
                $caseNumber  = 'CASE-' . $code . '-' . str_pad($caseCounter, 3, '0', STR_PAD_LEFT);

                $case = LegalCase::firstOrCreate(
                    ['case_number' => $caseNumber],
                    [
                        'status'           => $data['locale'] === 'en' ? $enStatuses[array_rand($enStatuses)] : $arStatuses[array_rand($arStatuses)],
                        'description'      => $description . ' #' . $caseCounter,
                        'court_id'         => $court->id,
                        'jurisdiction_id'  => $court->jurisdiction_id,
                        'court_level_id'   => $level->id,
                        'circuit'          => $data['locale'] === 'en' ? 'Division ' . rand(1, 5) : 'الدائرة رقم ' . rand(1, 5),
                        'total_costs'      => rand(1000, 5000),
                        'agreed_legal_fee' => rand(3000, 15000),
                        'deposit'          => rand(1000, 3000),
                        'rival_name'       => $rivalName,
                        'rival_number'     => '9' . rand(1000000, 9999999),
                        'rival_address'    => $data['address'],
                        'rival_nid'        => '10' . rand(10000000, 99999999),
                        'lawyer_id'        => $lawyer->id,
                        'created_at'       => $createdAt,
                        'updated_at'       => $createdAt,
                    ]
                );

                $case->clients()->syncWithoutDetaching([$client->id]);
                $case->lawyers()->syncWithoutDetaching([$lawyer->id => ['role' => 'lead']]);

                if (rand(1, 100) > 50) {
                    CaseExpense::firstOrCreate(
                        ['case_id' => $case->id, 'notes' => ($data['locale'] === 'en' ? 'Court filing fees #' : 'رسوم دعوى وإعلان ') . $caseCounter],
                        [
                            'user_id'     => $lawyerUser->id,
                            'amount'      => rand(100, 500),
                            'category'    => 'court_fees',
                            'status'      => rand(0, 1) ? 'approved' : 'pending',
                            'approved_by' => $admin->id,
                        ]
                    );
                }

                ClientPayment::firstOrCreate(
                    ['case_id' => $case->id, 'reference_number' => 'REF-' . $case->id],
                    [
                        'client_id'    => $client->id,
                        'mode'         => 'in_person',
                        'amount'       => $case->deposit,
                        'payment_date' => $createdAt->toDateString(),
                        'status'       => 'confirmed',
                        'notes'        => $data['locale'] === 'en' ? 'Initial retainer payment' : 'دفعة مقدمة',
                    ]
                );

                $apptDate = $createdAt->copy()->addDays(rand(10, 200));
                Appointment::firstOrCreate(
                    ['case_id' => $case->id, 'date' => $apptDate->toDateString()],
                    [
                        'time'      => str_pad(rand(8, 14), 2, '0', STR_PAD_LEFT) . ':00',
                        'notes'     => $data['locale'] === 'en' ? 'Hearing session' : 'جلسة مرافعة',
                        'client_id' => $client->id,
                    ]
                );

                $caseCounter++;
            }
        }
    }
}
