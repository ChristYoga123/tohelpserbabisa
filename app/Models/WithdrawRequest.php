<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WithdrawRequest extends Model
{
    protected $guarded = ['id'];

    public function karyawan()
    {
        return $this->belongsTo(User::class, 'karyawan_id');
    }
}
