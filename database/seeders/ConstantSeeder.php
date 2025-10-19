<?php

namespace Database\Seeders;

use App\Models\Constant;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class ConstantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Load JSON (choose one path)
        $path = public_path('/assets/currencies.json');
        // $path = storage_path('app/constants.json');

        if (! File::exists($path)) {
            $this->command?->warn("Constants JSON not found at: {$path}");
            return;
        }

        $raw = File::get($path);
        $items = json_decode($raw, true) ?? [];

        if (empty($items)) {
            $this->command?->warn("No constants found in JSON.");
            return;
        }
        $parent = Constant::create([
            'name' => 'currency',
            'key' => 'currency',
        ]);
        // OPTION A: create one-by-one via factory (simple & explicit)
        foreach ($items as $row) {
            // protect against missing keys
            $payload = [
                'parent_id'   => $parent->id,
                'key' => 'currency',
                'name'   => $row['name'],
                'value' => $row['code'],
                'extra'   => $row['symbol_native'],
            ];


            Constant::create($payload);
        }
    }
}
