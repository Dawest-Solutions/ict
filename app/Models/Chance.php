<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Chance extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'worker_id', 
        'reward_in_day_id', 
        'created_at', 
        'updated_at',
    ];

    /**
     * @return BelongsTo
     */
    public function rewardInDay(): BelongsTo
    {
        return $this->belongsTo(RewardInDay::class);
    }

    /**
     * @return BelongsTo
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * @param $query
     * @param $date
     * @return mixed
     */
    public function scopeToday($query,$date = null)
    {
        return $query->whereDate('created_at', $date ?? now());
    }

}
