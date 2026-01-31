<?php

use Illuminate\Support\Facades\Schema;

require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$hasCol = Schema::hasColumn('splaners', 'surat_keluar_id');
echo "Has surat_keluar_id: " . ($hasCol ? 'YES' : 'NO') . PHP_EOL;

$cols = Schema::getColumnListing('splaners');
echo "Columns: " . implode(', ', $cols) . PHP_EOL;
