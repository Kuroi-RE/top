<?php

namespace Tests\Feature\SystemEnhancements;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Base test case for System Enhancements feature tests.
 * All tests in this directory should extend this class.
 */
abstract class SystemEnhancementsTestCase extends TestCase
{
    use RefreshDatabase;
}
