<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KaryawanTugas extends Model
{
    protected $guarded = ['id'];

    public function karyawan()
    {
        return $this->belongsTo(User::class, 'karyawan_id');
    }

    public function tugas()
    {
        return $this->belongsTo(Transaksi::class, 'tugas_id');
    }
}
