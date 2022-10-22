<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
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
}
