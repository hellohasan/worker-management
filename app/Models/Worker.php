<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Worker extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'workers';

    /**
     * @var array
     */
    protected $fillable = [
        'user_id',
        'category_id',
        'country',
        'dob',
        'address',
        'passport',
        'passport_ex',
        'visa',
        'visa_ex',
        'charge',
        'status_id',
    ];

    /**
     * @var array
     */
    /* protected $casts = [
    'dob'         => 'date',
    'passport_ex' => 'date',
    'visa_ex'     => 'date',
    ]; */

    /**
     * Get the user that owns the Worker
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the category that owns the Worker
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * Get the status that owns the Worker
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

}
