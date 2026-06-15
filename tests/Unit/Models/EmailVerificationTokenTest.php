<?php

namespace Tests\Unit\Models;

use App\Models\EmailVerificationToken;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmailVerificationTokenTest extends TestCase
{
    use RefreshDatabase;

    public function test_fillable_attributes(): void
    {
        $model = new EmailVerificationToken();

        $this->assertEquals(['user_id', 'token', 'expires_at'], $model->getFillable());
    }

    public function test_timestamps_are_disabled(): void
    {
        $model = new EmailVerificationToken();

        $this->assertFalse($model->usesTimestamps());
    }

    public function test_expires_at_is_cast_to_datetime(): void
    {
        $model = new EmailVerificationToken();
        $casts = $model->getCasts();

        $this->assertEquals('datetime', $casts['expires_at']);
        $this->assertEquals('datetime', $casts['used_at']);
    }

    public function test_user_relationship(): void
    {
        $user = User::factory()->create();

        $token = EmailVerificationToken::create([
            'user_id' => $user->id_user,
            'token' => EmailVerificationToken::generateToken(),
            'expires_at' => now()->addHours(24),
        ]);

        $this->assertInstanceOf(User::class, $token->user);
        $this->assertEquals($user->id_user, $token->user->id_user);
    }

    public function test_is_valid_returns_true_for_unused_unexpired_token(): void
    {
        $user = User::factory()->create();

        $token = EmailVerificationToken::create([
            'user_id' => $user->id_user,
            'token' => EmailVerificationToken::generateToken(),
            'expires_at' => now()->addHours(24),
        ]);

        $this->assertTrue($token->isValid());
    }

    public function test_is_valid_returns_false_for_used_token(): void
    {
        $user = User::factory()->create();

        $token = EmailVerificationToken::create([
            'user_id' => $user->id_user,
            'token' => EmailVerificationToken::generateToken(),
            'expires_at' => now()->addHours(24),
        ]);

        $token->used_at = now();
        $token->save();

        $this->assertFalse($token->fresh()->isValid());
    }

    public function test_is_valid_returns_false_for_expired_token(): void
    {
        $user = User::factory()->create();

        $token = EmailVerificationToken::create([
            'user_id' => $user->id_user,
            'token' => EmailVerificationToken::generateToken(),
            'expires_at' => now()->subHour(),
        ]);

        $this->assertFalse($token->isValid());
    }

    public function test_generate_token_returns_64_char_hex_string(): void
    {
        $token = EmailVerificationToken::generateToken();

        $this->assertIsString($token);
        $this->assertEquals(64, strlen($token));
        $this->assertMatchesRegularExpression('/^[0-9a-f]{64}$/', $token);
    }

    public function test_generate_token_produces_unique_values(): void
    {
        $token1 = EmailVerificationToken::generateToken();
        $token2 = EmailVerificationToken::generateToken();

        $this->assertNotEquals($token1, $token2);
    }
}
