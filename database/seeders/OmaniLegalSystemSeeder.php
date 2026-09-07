<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Jurisdiction;
use App\Models\CourtLevel;
use App\Models\Court;
use App\Models\CourtDivision;

class OmaniLegalSystemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. جهات التقاضي والجهات العدلية (Jurisdictions)
        $jurisdictionsData = [
            'ordinary'      => 'جهات القضاء العادي (درجات التقاضي)',
            'specialized'   => 'جهات القضاء المتخصص واللجان شبه القضائية',
            'investigation' => 'جهات التحقيق والإنفاذ والتوثيق العدلي',
            'police'        => 'مراكز شرطة عمان السلطانية',
            'government'    => 'المؤسسات والهيئات الحكومية (جهات الترافع الإداري والخبراء)',
        ];

        $jurisdictions = [];
        foreach ($jurisdictionsData as $key => $name) {
            $jurisdictions[$key] = Jurisdiction::firstOrCreate(['name' => $name]);
        }

        // 2. درجات التقاضي (Court Levels)
        $courtLevelsData = [
            'محاكم الدرجة الأولى (المحاكم الابتدائية)',
            'محاكم الدرجة الثانية (محاكم الاستئناف)',
            'المحكمة العليا',
        ];

        foreach ($courtLevelsData as $levelName) {
            CourtLevel::firstOrCreate(['name' => $levelName]);
        }

        // 3. المحاكم والجهات العدلية بحسب جهة التقاضي (Courts & Entities)
        $courtsMapping = [
            'ordinary' => [
                'المحكمة الابتدائية بمسقط (الخوير)',
                'المحكمة الابتدائية بالسيب',
                'المحكمة الابتدائية بقريات',
                'المحكمة الابتدائية بالعامرات',
                'محكمة الاستئناف بمسقط',
                'محكمة الاستئناف بالسيب',
                'المحكمة العليا (مقرها مسقط)',
            ],
            'specialized' => [
                'محكمة القضاء الإداري',
                'لجان التوفيق والمصالحة',
                'لجنة تسوية المنازعات العمالية',
                'لجنة الفصل في المخالفات الجمركية',
                'لجنة الاعتراضات الضريبية',
                'مركز عمان للتحكيم التجاري',
            ],
            'investigation' => [
                'الادعاء العام - إدارة قضايا الأموال العامة',
                'الادعاء العام - إدارة قضايا المخدرات',
                'إدارات الادعاء العام في الولايات',
                'إدارة التنفيذ بالمحاكم',
                'دوائر الكاتب بالعدل',
            ],
            'police' => [
                'مراكز الشرطة بحسب المنطقة',
            ],
            'government' => [
                'وزارة العدل والشؤون القانونية',
                'دائرة الخبراء بالمجلس الأعلى للقضاء',
                'وزارة التجارة والصناعة وترويج الاستثمار',
                'وزارة الإسكان والتخطيط العمراني',
                'جهاز الضرائب',
            ],
        ];

        $createdCourts = [];
        foreach ($courtsMapping as $jurKey => $courtList) {
            $jur = $jurisdictions[$jurKey];
            foreach ($courtList as $courtName) {
                $createdCourts[$courtName] = Court::firstOrCreate([
                    'name'            => $courtName,
                    'jurisdiction_id' => $jur->id,
                ]);
            }
        }

        // 4. الدوائر القضائية (Court Divisions)
        $divisionsData = [
            'الدائرة المدنية',
            'الدائرة التجارية',
            'الدائرة العمالية',
            'الدائرة الشرعية (الأحوال الشخصية)',
            'الدائرة الجزائية (الجنح والجنايات)',
            'الدائرة الإيجارية',
            'الدوائر الثلاثية',
            'الدوائر الفردية',
        ];

        $primaryCourt = $createdCourts['المحكمة الابتدائية بمسقط (الخوير)'] ?? Court::first();

        if ($primaryCourt) {
            foreach ($divisionsData as $divisionName) {
                CourtDivision::firstOrCreate([
                    'name'     => $divisionName,
                    'court_id' => $primaryCourt->id,
                ]);
            }
        }
    }
}
