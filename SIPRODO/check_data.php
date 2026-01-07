<?php
use App\Models\PengabdianMasyarakat;
use App\Models\User;
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "--- Pengabdian Masyarakat Data ---\n";
$items = PengabdianMasyarakat::take(5)->get();
foreach ($items as $i) {
    echo 'Tim: ' . json_encode($i->tim_abdimas) . "\n";
    echo 'NIP: ' . json_encode($i->dosen_nip) . "\n---\n";
}

echo "\n--- User Check ---\n";
$u = User::where('name', 'like', '%Luthfi%')->first();
echo 'User: ' . ($u ? $u->name . ' - ' . $u->nip : 'Not Found') . "\n";
