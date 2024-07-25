<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $vehicle_id
 * @property Carbon $contract_date
 * @property Carbon $expiration_date
 * @property double $amount
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class InsurancePayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_id',
        'contract_date',
        'expiration_date',
        'amount',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * @return BelongsTo
     */
    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }
}
