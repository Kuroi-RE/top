<?php

namespace Tests\Feature\SystemEnhancements;

/**
 * Smoke test to verify the SystemEnhancements test infrastructure is set up correctly.
 * This test confirms PHPUnit can discover and run tests in this directory,
 * and that the eris PBT library is available.
 */
class InfrastructureTest extends SystemEnhancementsTestCase
{
    public function test_eris_library_is_installed(): void
    {
        // Eris\TestTrait is a PHP trait, so we use trait_exists() not class_exists()
        $this->assertTrue(
            trait_exists(\Eris\TestTrait::class),
            'The giorgiosironi/eris PBT library should be installed and autoloaded.'
        );
    }

    public function test_system_enhancements_test_directory_is_discoverable(): void
    {
        $this->assertTrue(true, 'PHPUnit can discover tests in tests/Feature/SystemEnhancements/');
    }
}
