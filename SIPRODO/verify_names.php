<?php
use App\Models\User;
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "--- Users After Normalization ---\n\n";
$users = User::where('role', 'dosen')->orderBy('nip')->get();
foreach ($users as $u) {
    echo sprintf("NIP: %-12s | Name: %s\n", $u->nip, $u->name);
}
