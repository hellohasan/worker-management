<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
        'custom',
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

    /**
     * @return mixed
     */
    public function getPassportStatus()
    {
        if (Carbon::parse($this->passport_ex)->isPast()) {
            $expire = 'Expired';
        } else {
            $expire = Carbon::parse($this->passport_ex)->diff(Carbon::now())->format('%yy %mm %dd');
        }
        return $expire;
    }

    /**
     * @return mixed
     */
    public function getVisaStatus()
    {
        if (Carbon::parse($this->visa_ex)->isPast()) {
            $expire = 'Expired';
        } else {
            $expire = Carbon::parse($this->visa_ex)->diff(Carbon::now())->format('%yy %mm %dd');
        }
        return $expire;
    }

    /**
     * @return mixed
     */
    public function getAge()
    {
        return Carbon::parse($this->dob)->diff(Carbon::now())->format('%y Years %m Month %d Days');
    }

    public function getShortAge()
    {
        return Carbon::parse($this->dob)->diff(Carbon::now())->format('%yy %mm %dd');
    }

    /**
     * Get all of the orders for the Worker
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders(): HasMany
    {
        return $this->hasMany(OrderWorker::class, 'worker_id', 'id');
    }

}
