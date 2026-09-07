<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CourtLevelSeeder extends Seeder
{
    public function run(): void
    {
        if (DB::table('court_levels')->count() > 0) {
            $this->command->info('court_levels مليانة بالفعل، تم التخطي.');
            return;
        }

        DB::table('court_levels')->insert([
            ['name' => 'ابتدائي',                  'created_at' => now(), 'updated_at' => now()],
            ['name' => 'استئناف',                  'created_at' => now(), 'updated_at' => now()],
            ['name' => 'نقض',                      'created_at' => now(), 'updated_at' => now()],
            ['name' => 'إداري ابتدائي',            'created_at' => now(), 'updated_at' => now()],
            ['name' => 'إداري استئناف',            'created_at' => now(), 'updated_at' => now()],
            ['name' => 'المحكمة الإدارية العليا',  'created_at' => now(), 'updated_at' => now()],
            ['name' => 'دستوري',                   'created_at' => now(), 'updated_at' => now()],
            ['name' => 'اقتصادي ابتدائي',          'created_at' => now(), 'updated_at' => now()],
            ['name' => 'اقتصادي استئناف',          'created_at' => now(), 'updated_at' => now()],
            ['name' => 'أسرة ابتدائي',             'created_at' => now(), 'updated_at' => now()],
            ['name' => 'أسرة استئناف',             'created_at' => now(), 'updated_at' => now()],
            ['name' => 'عسكري ابتدائي',            'created_at' => now(), 'updated_at' => now()],
            ['name' => 'عسكري استئناف',            'created_at' => now(), 'updated_at' => now()],
            ['name' => 'عسكري عليا',               'created_at' => now(), 'updated_at' => now()],
        ]);

        $this->command->info('تم إدراج درجات التقاضي بنجاح.');
    }
}