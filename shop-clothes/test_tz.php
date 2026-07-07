<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$token = DB::table('password_reset_tokens')->first();
if($token) {
    $parsed = \Carbon\Carbon::parse($token->created_at);
    echo 'parsed: ' . $parsed . "\n";
    echo 'now: ' . \Carbon\Carbon::now() . "\n";
    echo 'diff in seconds: ' . \Carbon\Carbon::now()->diffInSeconds($parsed, false) . "\n";
} else {
    echo "No token in DB.\n";
}
