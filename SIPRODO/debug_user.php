<?php
use App\Models\User;
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "--- Debug User Lookup ---\n";
// Check specific NIP
$u = User::where('nip', '20890020')->first();
if ($u) {
    echo "Found by NIP 20890020: [{$u->id}] {$u->name} (Role: {$u->role})\n";
} else {
    echo "NOT FOUND by NIP 20890020\n";
}

// Check by Name
$u2 = User::where('name', 'like', '%Luthfi%')->get();
echo "Found " . $u2->count() . " users with name like Luthfi:\n";
foreach ($u2 as $user) {
    echo "- [{$user->id}] {$user->name} (NIP: {$user->nip}) (Role: {$user->role})\n";
}

// Check total users and roles
echo "\nTotal Users: " . User::count() . "\n";
echo "Roles breakdown:\n";
$roles = User::select('role', \DB::raw('count(*) as total'))->groupBy('role')->get();
foreach ($roles as $r) {
    echo "{$r->role}: {$r->total}\n";
}
