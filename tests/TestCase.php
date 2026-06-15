<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Laravel\Sanctum\Sanctum;

abstract class TestCase extends BaseTestCase
{
    /**
     * Authenticate as a user for API testing.
     *
     * DEF-003 FIX: Routes use 'auth:api' guard (with sanctum driver from config/auth.php).
     * Using Sanctum::actingAs() is the correct way to authenticate in tests —
     * it bypasses guard entirely and uses Sanctum's token resolution directly.
     * Do NOT use $this->actingAs($user, 'sanctum') — guard 'sanctum' does not exist in config.
     * Do NOT use $this->actingAs($user, 'api') unless the test specifically needs guard-based auth.
     *
     * @param User $user
     * @param array $abilities
     * @return static
     */
    protected function actingAsUser(User $user, array $abilities = ['*']): static
    {
        Sanctum::actingAs($user, $abilities);
        return $this;
    }
}
