<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Lawyer;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_endpoints_are_disabled()
    {
        $responseGet = $this->get('/register');
        $responseGet->assertStatus(404);

        $responsePost = $this->post('/register', [
            'name' => 'Hacker',
            'email' => 'hacker@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);
        $responsePost->assertStatus(404);
    }

    public function test_lawyer_cannot_access_lawyer_create_page()
    {
        $lawyerUser = User::factory()->create(['role' => 'lawyer']);

        $response = $this->actingAs($lawyerUser)->get(route('lawyers.create'));
        $response->assertStatus(403);
    }

    public function test_lawyer_cannot_provision_new_lawyer()
    {
        $lawyerUser = User::factory()->create(['role' => 'lawyer']);

        $response = $this->actingAs($lawyerUser)->post(route('lawyers.store'), [
            'name' => 'New Lawyer',
            'email' => 'newlawyer@example.com',
            'phone' => '01012345678',
            'specialization' => 'مدني',
            'license_number' => '123456',
            'address' => 'شارع التحرير أسوان',
            'degree' => 'ابتدائي',
            'bio' => 'نبذة تعريفية للمحامي الجديد لا تقل عن عشرين حرفاً',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(403);
    }

    public function test_non_admin_cannot_access_system_settings()
    {
        $lawyerUser = User::factory()->create(['role' => 'lawyer']);

        $response = $this->actingAs($lawyerUser)->get(route('settings.edit'));
        $response->assertStatus(403);
    }

    public function test_global_helper_returns_firm_name()
    {
        $this->assertEquals(firm_name(), config('app.name', 'الميزان'));

        \App\Models\Setting::set('app_name', 'مكتب العدالة الدولية');
        $this->assertEquals(firm_name(), 'مكتب العدالة الدولية');
    }

    public function test_demo_exit_logs_out_clears_session_and_redirects_to_login()
    {
        $user = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($user)
            ->withSession(['is_demo' => true])
            ->post(route('demo.exit'));

        $response->assertRedirect(route('login'));
        $this->assertGuest();
        $this->assertFalse(session()->has('is_demo'));
    }
}
