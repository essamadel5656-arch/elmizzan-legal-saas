<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AiFeatureGatingTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::create([
            'name'     => 'محامٍ مستخدم',
            'email'    => 'user@elmizzan.test',
            'password' => bcrypt('password'),
            'role'     => 'lawyer',
        ]);
    }

    public function test_ai_endpoint_blocked_when_feature_disabled(): void
    {
        Setting::set('ai_feature_enabled', '0');

        $response = $this->actingAs($this->user)
            ->postJson(route('chat.create_session'));

        $response->assertStatus(403)
            ->assertJson([
                'error' => 'AI feature is not enabled for this firm.',
            ]);
    }

    public function test_ai_endpoint_accessible_when_feature_enabled(): void
    {
        Setting::set('ai_feature_enabled', '1');

        $response = $this->actingAs($this->user)
            ->postJson(route('chat.create_session'));

        $response->assertStatus(200)
            ->assertJsonStructure(['session_id', 'title']);
    }

    public function test_chat_send_returns_403_when_disabled(): void
    {
        Setting::set('ai_feature_enabled', '0');

        $response = $this->actingAs($this->user)
            ->postJson(route('chat.send'), [
                'message' => 'ما هي شروط صحة عقد الإيجار؟',
            ]);

        $response->assertStatus(403)
            ->assertJson([
                'error' => 'AI feature is not enabled for this firm.',
            ]);
    }
}
