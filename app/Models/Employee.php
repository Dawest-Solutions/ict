<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Employee extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 
        'last_name', 
        'phone', 
        'registration_code',
        'type', 
        'years_of_employment', 
        'end_of_work', 
        'password',
        'agreement_1', 
        'agreement_1_text', 
        'agreement_2', 
        'agreement_2_text', 
        'active', 
        'registered_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * @return HasMany
     */
    public function chances(): HasMany
    {
        return $this->hasMany(Chance::class, 'employee_id');
    }

    /**
     * @return HasMany
     */
    public function winner(): HasMany
    {
        return $this->hasMany(Winner::class, 'employee_id');
    }

    public function user_voucher()
    {
        return $this->hasOne(UserVoucher::class, 'user_id', 'id');
    }

    /**
     * @return bool
     */
    public function isWinner(): bool
    {
        if (in_array($this->id, [322, 383, 182, 46])) {
            return true;
        }

        return $this->winner()->count() > 0;
    }
}
