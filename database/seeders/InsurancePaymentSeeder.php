<?php

namespace Database\Seeders;

use App\Models\InsurancePayment;
use Illuminate\Database\Seeder;

class InsurancePaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        InsurancePayment::factory()->count(50)->create();
    }
}
