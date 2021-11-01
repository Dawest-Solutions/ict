<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * App\Models\User
 *
 * @property int $id
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string $phone
 * @property string $registration_code
 * @property string $type
 * @property int $years_of_employment
 * @property string|null $end_of_work
 * @property string|null $password
 * @property string|null $remember_token
 * @property string|null $registered_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @method static \Database\Factories\UserFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Worker newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Worker newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Worker query()
 * @method static \Illuminate\Database\Eloquent\Builder|Worker whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Worker whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Worker whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Worker wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Worker whereRegistrationCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Worker whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Worker whereYearsOfEmployment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Worker whereEndOfWork($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Worker whereRegisteredAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Worker whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Worker whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Worker extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
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
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
