<?php

use App\Models\Vehicle;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('insurance_payments')) {
            Schema::create('insurance_payments', function (Blueprint $table) {
                $table->id();
                $table->foreignIdFor(Vehicle::class);
                $table->date('contract_date');
                $table->date('expiration_date');
                $table->double('amount');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('insurance_payments');
    }
};
