<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Renames permission display names from Indonesian/mixed to English
     * for all Proposal, LPJ, Revision Proposal, and Template Document permissions.
     *
     * Only affects permissions with guard_name = 'api'.
     */
    public function up(): void
    {
        $renames = [
            // Proposal permissions
            'Create Proposal Kegiatan' => 'Create Proposal',
            'View Proposal Kegiatan'   => 'View Proposal',
            'Edit Proposal Kegiatan'   => 'Edit Proposal',
            'Delete Proposal Kegiatan' => 'Delete Proposal',
            'Approve Proposal Kegiatan' => 'Approve Proposal',
            'Reject Proposal Kegiatan'  => 'Reject Proposal',

            // LPJ permissions
            'Create LPJ Kegiatan' => 'Create LPJ',
            'View LPJ Kegiatan'   => 'View LPJ',
            'Edit LPJ Kegiatan'   => 'Edit LPJ',
            'Delete LPJ Kegiatan' => 'Delete LPJ',
            'Approve LPJ Kegiatan' => 'Approve LPJ',
            'Reject LPJ Kegiatan'  => 'Reject LPJ',

            // Revision Proposal permissions
            'View Revisi Proposal'    => 'View Revision Proposal',
            'Edit Revisi Proposal'    => 'Edit Revision Proposal',
            'Approve Revisi Proposal' => 'Approve Revision Proposal',

            // Template Document permissions
            'Manage Template Dokumen' => 'Manage Template Documents',
            'View Template Dokumen'   => 'View Template Documents',
        ];

        foreach ($renames as $oldName => $newName) {
            DB::statement(
                "UPDATE permissions SET name = ? WHERE name = ? AND guard_name = 'api'",
                [$newName, $oldName]
            );
        }
    }

    /**
     * Reverse the migrations.
     *
     * Reverts permission display names from English back to the original
     * Indonesian/mixed names.
     */
    public function down(): void
    {
        $reverts = [
            // Proposal permissions
            'Create Proposal' => 'Create Proposal Kegiatan',
            'View Proposal'   => 'View Proposal Kegiatan',
            'Edit Proposal'   => 'Edit Proposal Kegiatan',
            'Delete Proposal' => 'Delete Proposal Kegiatan',
            'Approve Proposal' => 'Approve Proposal Kegiatan',
            'Reject Proposal'  => 'Reject Proposal Kegiatan',

            // LPJ permissions
            'Create LPJ' => 'Create LPJ Kegiatan',
            'View LPJ'   => 'View LPJ Kegiatan',
            'Edit LPJ'   => 'Edit LPJ Kegiatan',
            'Delete LPJ' => 'Delete LPJ Kegiatan',
            'Approve LPJ' => 'Approve LPJ Kegiatan',
            'Reject LPJ'  => 'Reject LPJ Kegiatan',

            // Revision Proposal permissions
            'View Revision Proposal'    => 'View Revisi Proposal',
            'Edit Revision Proposal'    => 'Edit Revisi Proposal',
            'Approve Revision Proposal' => 'Approve Revisi Proposal',

            // Template Document permissions
            'Manage Template Documents' => 'Manage Template Dokumen',
            'View Template Documents'   => 'View Template Dokumen',
        ];

        foreach ($reverts as $currentName => $originalName) {
            DB::statement(
                "UPDATE permissions SET name = ? WHERE name = ? AND guard_name = 'api'",
                [$originalName, $currentName]
            );
        }
    }
};
