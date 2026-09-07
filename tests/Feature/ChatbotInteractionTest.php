<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ChatbotInteractionTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        Setting::set('app_name', 'الميزان');
        Setting::set('ai_feature_enabled', '1');

        $this->user = User::create([
            'name'     => 'محامٍ باحث',
            'email'    => 'researcher@elmizzan.test',
            'password' => bcrypt('password'),
            'role'     => 'lawyer',
        ]);
    }

    public function test_suggestion_chips_are_present_in_view(): void
    {
        $response = $this->actingAs($this->user)->get(route('chat.index'));

        $response->assertStatus(200);
        $response->assertSee('صياغة عقد إيجار');
        $response->assertSee('إجراءات الدعوى');
        $response->assertSee('مراجعة بند قانوني');
        $response->assertSee('fillSuggestion');
        $response->assertSee('submitMessage');
    }

    public function test_chat_send_requires_auth(): void
    {
        $response = $this->post(route('chat.send'), [
            'message' => 'استشارة قانونية بدون تسجيل دخول',
        ]);

        $response->assertRedirect('/login');
    }
}
