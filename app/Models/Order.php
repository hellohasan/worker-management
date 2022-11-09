<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'orders';

    /**
     * @var array
     */
    protected $fillable = [
        'custom',
        'company_id',
        'total',
        'payment_at',
        'status',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'payment_at' => 'datetime',
    ];

    /**
     * Get the company that owns the Order
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    /**
     * Get all of the workers for the Order
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function workers(): HasMany
    {
        return $this->hasMany(OrderWorker::class, 'order_id');
    }
}
