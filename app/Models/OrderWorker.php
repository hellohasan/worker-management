<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderWorker extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'order_workers';

    /**
     * @var array
     */
    protected $fillable = [
        'order_id',
        'worker_id',
    ];

    /**
     * @return mixed
     */
    public function worker()
    {
        return $this->belongsTo(Worker::class, 'worker_id');
    }

    /**
     * Get the order that owns the OrderWorker
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
