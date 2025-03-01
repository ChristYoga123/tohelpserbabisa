<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $guarded = ['id'];

    public function tugas()
    {
        return $this->belongsToMany(User::class, 'karyawan_tugas', 'transaksi_id', 'karyawan_id')->withPivot('id');
    }

    public function voucher()
    {
        return $this->belongsTo(Voucher::class);
    }
}
