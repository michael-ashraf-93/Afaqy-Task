<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $vehicle_id
 * @property Carbon $star_at
 * @property Carbon $end_at
 * @property string $invoice_number
 * @property string $purchase_order_number
 * @property string $status // enum('open','in-progress','closed')
 * @property double $discount
 * @property double $tax
 * @property double $total
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_id',
        'star_at',
        'end_at',
        'invoice_number',
        'purchase_order_number',
        'status',
        'discount',
        'tax',
        'total'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * @return BelongsTo
     */
    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }
}
