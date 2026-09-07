<?php

use App\Models\Appointment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

it('shows only appointments within the next 7 days on the notifications page', function () {
    $lawyerId = DB::table('lawyers')->insertGetId([
        'name' => 'Test Lawyer',
        'email' => 'lawyer@example.com',
        'phone' => '123456789',
        'specialization' => 'Civil',
        'license_number' => 'LIC-1',
        'address' => 'Address',
        'degree' => 'ابتدائي',
        'national_id_image' => null,
        'bar_card_image' => null,
        'profile_image' => null,
        'bio' => 'Bio',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $user = User::create([
        'name' => 'Test Lawyer',
        'email' => 'lawyer@example.com',
        'password' => bcrypt('password'),
        'role' => 'lawyer',
        'lawyer_id' => $lawyerId,
    ]);

    $jurisdictionId = DB::table('jurisdictions')->insertGetId([
        'name' => 'Test Jurisdiction',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $courtId = DB::table('courts')->insertGetId([
        'name' => 'Test Court',
        'jurisdiction_id' => $jurisdictionId,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $caseId = DB::table('cases')->insertGetId([
        'case_number' => 'CASE-1',
        'status' => 'مفتوحة',
        'description' => 'Test case',
        'costs' => 0,
        'court_id' => $courtId,
        'lawyer_id' => $lawyerId,
        'total_costs' => 0,
        'deposit' => 0,
        'Previous_procedure' => 'Initial',
        'case_file' => '',
        'final_decision' => '',
        'notes' => 'Test notes',
        'rival_name' => 'Opponent',
        'rival_number' => '123',
        'rival_address' => 'Address',
        'rival_nid' => '987654321',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    DB::table('lawyerscases')->insert([
        'lawyer_id' => $lawyerId,
        'case_id' => $caseId,
        'role' => 'lead',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    Appointment::create([
        'date' => now()->addDays(2)->toDateString(),
        'notes' => 'Near appointment',
        'case_id' => $caseId,
    ]);

    Appointment::create([
        'date' => now()->addDays(10)->toDateString(),
        'notes' => 'Far appointment',
        'case_id' => $caseId,
    ]);

    $this->actingAs($user)
        ->get('/appointments/notifications')
        ->assertOk()
        ->assertSee('Near appointment')
        ->assertDontSee('Far appointment');
});
