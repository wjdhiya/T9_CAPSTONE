<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PengabdianMasyarakat;
use App\Models\Penelitian;
use App\Models\Publikasi;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class NormalizeUserNamesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * This seeder extracts real names from activity records (Pengmas, Penelitian, Publikasi)
     * and updates the users table to replace placeholder names like "Dosen {NIP}" with
     * actual names, while keeping NIP as the primary identifier.
     */
    public function run(): void
    {
        $this->command->info('Starting user name normalization...');

        // Step 1: Build NIP -> Name mapping from Pengabdian Masyarakat
        $nipToNameMap = [];

        $this->command->info('Extracting names from Pengabdian Masyarakat...');
        $pengmasRecords = PengabdianMasyarakat::all();

        foreach ($pengmasRecords as $record) {
            $names = is_array($record->tim_abdimas) ? $record->tim_abdimas : [];
            $nips = is_array($record->dosen_nip) ? $record->dosen_nip : [];

            // Map parallel arrays
            foreach ($names as $index => $name) {
                if (isset($nips[$index]) && !empty($nips[$index])) {
                    $nip = trim($nips[$index]);
                    $cleanName = trim(preg_replace('/ NIP$/i', '', trim($name)));

                    // Only store if it looks like a real name (not empty, not just NIP)
                    if (!empty($cleanName) && !preg_match('/^Dosen\s+\d+$/i', $cleanName)) {
                        // Use title case for consistency
                        $cleanName = ucwords(strtolower($cleanName));

                        // If we haven't seen this NIP, or this name is longer (more complete), store it
                        if (!isset($nipToNameMap[$nip]) || strlen($cleanName) > strlen($nipToNameMap[$nip])) {
                            $nipToNameMap[$nip] = $cleanName;
                        }
                    }
                }
            }
        }

        $this->command->info('Found ' . count($nipToNameMap) . ' NIP-to-Name mappings from Pengmas');

        // Step 2: Try to enhance with Penelitian data (names without NIPs, but we can try fuzzy matching)
        // For now, skip Penelitian/Publikasi as they don't have NIP arrays

        // Step 3: Update users table
        $updateCount = 0;
        $skippedCount = 0;

        foreach ($nipToNameMap as $nip => $realName) {
            $user = User::where('nip', $nip)->first();

            if ($user) {
                // Only update if current name is a placeholder
                if (preg_match('/^Dosen\s+\d+$/i', $user->name)) {
                    $oldName = $user->name;
                    $user->name = $realName;
                    $user->save();

                    $this->command->info("Updated NIP {$nip}: '{$oldName}' -> '{$realName}'");
                    $updateCount++;
                } else {
                    $this->command->comment("Skipped NIP {$nip}: Already has name '{$user->name}'");
                    $skippedCount++;
                }
            } else {
                $this->command->warn("NIP {$nip} not found in users table");
            }
        }

        $this->command->info("\nNormalization complete!");
        $this->command->info("Updated: {$updateCount}");
        $this->command->info("Skipped: {$skippedCount}");
        $this->command->info("Total processed: " . ($updateCount + $skippedCount));
    }
}
