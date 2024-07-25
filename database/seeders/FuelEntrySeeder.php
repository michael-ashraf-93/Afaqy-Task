<?php

namespace Database\Seeders;

use App\Models\FuelEntry;
use Illuminate\Database\Seeder;

class FuelEntrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        FuelEntry::factory()->count(50)->create();
    }
}
