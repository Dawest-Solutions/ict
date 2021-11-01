<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Winner extends Model
{
    use HasFactory;

    public $fillable = [
        'employee_id',
        'reward_in_day_id',
        'date_draw'
    ];

    public function rewardInDay(): BelongsTo
    {
        return $this->belongsTo(RewardInDay::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
