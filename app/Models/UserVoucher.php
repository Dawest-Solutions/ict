<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserVoucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'voucher_id'
    ];

    public function user()
    {
        return $this->belongsTo(Employee::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'user_id', 'id');
    }

    public function voucher()
    {
        return $this->belongsTo(Voucher::class);
    }
}
