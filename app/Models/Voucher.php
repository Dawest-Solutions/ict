<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;

    public function employees()
    {
        $this->hasMany(UserVoucher::class, 'user_id', 'id');
    }
}
