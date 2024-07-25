<?php

namespace App\Models;

use App\Models\Views\VehicleExpense;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property string $plate_number
 * @property string $imei
 * @property string $vin
 * @property int $year
 * @property string $license
 * @property Carbon $crated_at
 * @property Carbon $updated_at
 */
class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'plate_number',
        'imei',
        'vin',
        'year',
        'license'
    ];

    protected $casts = [
        'crated_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * @return HasMany
     */
    public function fuelEntries(): HasMany
    {
        return $this->hasMany(FuelEntry::class);
    }

    /**
     * @return HasMany
     */
    public function insurancePayments(): HasMany
    {
        return $this->hasMany(InsurancePayment::class);
    }

    /**
     * @return HasMany
     */
    public function services(): HasMany
    {
        return $this->hasMany(Service::class);
    }

    /**
     * @return HasMany
     */
    public function expenses(): HasMany
    {
        return $this->hasMany(VehicleExpense::class);
    }
}
