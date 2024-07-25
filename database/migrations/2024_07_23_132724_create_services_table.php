<?php

use App\Enum\ServiceStatusEnums;
use App\Models\Service;
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
        if (!Schema::hasTable('services')) {
            Schema::create('services', function (Blueprint $table) {
                $table->id();
                $table->foreignIdFor(Vehicle::class, 'vehicle_id');
                $table->timestamp('start_date');
                $table->timestamp('end_date');
                $table->string('invoice_number');
                $table->string('purchase_order_number');
                $table->enum('status', [ServiceStatusEnums::STATUS_OPEN, ServiceStatusEnums::STATUS_IN_PROGRESS, ServiceStatusEnums::STATUS_CLOSED]);
                $table->double('discount');
                $table->double('tax');
                $table->double('total');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
