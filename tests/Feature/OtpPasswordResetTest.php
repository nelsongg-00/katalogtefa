<?php

namespace Tests\Feature;

use App\Models\User;
use App\Notifications\VerifyOtpNotification;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class OtpPasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_otp_screen_can_be_rendered(): void
    {
        $this->get('/verify-otp')->assertOk();
    }

    public function test_forgot_password_sends_otp_and_redirects_to_verification(): void
    {
        Notification::fake();

        $user = User::factory()->create();

        $this->post('/forgot-password', ['email' => $user->email])
            ->assertRedirect(route('password.otp.show'));

        // Link reset Breeze tetap terkirim (alur lama tidak rusak).
        Notification::assertSentTo($user, ResetPassword::class);
        Notification::assertSentOnDemand(VerifyOtpNotification::class);

        $this->assertSame($user->email, session('email'));
        $this->assertMatchesRegularExpression('/^\d{6}$/', session('otp'));
    }

    public function test_otp_is_written_to_log_immediately(): void
    {
        Notification::fake();

        $log = storage_path('logs/otp.log');
        @unlink($log);

        $user = User::factory()->create();

        $this->post('/forgot-password', ['email' => $user->email])
            ->assertRedirect(route('password.otp.show'));

        $this->assertFileExists($log);

        $content = file_get_contents($log);
        $this->assertStringContainsString('Kode    : '.session('otp'), $content);
        $this->assertStringContainsString($user->email, $content);
    }

    public function test_correct_otp_redirects_to_reset_password_with_token(): void
    {
        $user = User::factory()->create();

        $response = $this->withSession([
            'email' => $user->email,
            'otp' => '123456',
            'otp_expires_at' => now()->addMinutes(5)->timestamp,
        ])->post('/verify-otp', ['otp' => '123456']);

        $response->assertSessionMissing('otp');
        $this->assertTrue(
            DB::table('password_reset_tokens')->where('email', $user->email)->exists()
        );

        $this->get($response->headers->get('Location'))->assertOk();
    }

    public function test_wrong_otp_shows_error(): void
    {
        $user = User::factory()->create();

        $this->withSession([
            'email' => $user->email,
            'otp' => '123456',
            'otp_expires_at' => now()->addMinutes(5)->timestamp,
        ])->post('/verify-otp', ['otp' => '654321'])
            ->assertSessionHasErrors('otp');
    }

    public function test_expired_otp_redirects_to_forgot_password(): void
    {
        $user = User::factory()->create();

        $this->withSession([
            'email' => $user->email,
            'otp' => '123456',
            'otp_expires_at' => now()->subMinute()->timestamp,
        ])->post('/verify-otp', ['otp' => '123456'])
            ->assertRedirect(route('password.request'))
            ->assertSessionHasErrors('otp');
    }

    public function test_reset_password_screen_renders_hidden_email_from_session(): void
    {
        $this->withSession(['email' => 'user@example.com'])
            ->get('/reset-password/some-token')
            ->assertOk()
            ->assertSee('name="email" value="user@example.com"', false);
    }

    public function test_resend_issues_a_new_otp(): void
    {
        Notification::fake();

        $user = User::factory()->create();

        $this->withSession(['email' => $user->email])
            ->post('/resend-otp')
            ->assertRedirect(route('password.otp.show'))
            ->assertSessionHas('status');

        Notification::assertSentOnDemand(VerifyOtpNotification::class);
        $this->assertMatchesRegularExpression('/^\d{6}$/', session('otp'));
    }
}
