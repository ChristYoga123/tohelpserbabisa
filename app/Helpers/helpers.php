<?php 

use App\Models\TarifDasar;
use App\Models\TarifJarak;

function getPricing(string $tipe, $jarakBaseCampKeTitikJemput, $jarakTitikJemputKeTitikTujuan, $discount = null)
{
    $tarifDasar = TarifDasar::whereJenis($tipe)->first();
    
    // Perbaikan di sini: gunakan whereRaw untuk mengecek range
    $tarifJarak = TarifJarak::whereJenis($tipe)
        ->where(function($query) use ($jarakTitikJemputKeTitikTujuan) {
            $query->where('jarak_min', '<=', $jarakTitikJemputKeTitikTujuan)
                  ->where(function($q) use ($jarakTitikJemputKeTitikTujuan) {
                      $q->where('jarak_max', '>=', $jarakTitikJemputKeTitikTujuan)
                        ->orWhereNull('jarak_max'); // Untuk handle kasus "seterusnya"
                  });
        })
        ->first();
    $harga = 0;

    if ($tarifJarak) {
        // Jika jarak dari basecamp ke titik jemput lebih dari 5 km maka akan dapat gratis 5km pertama
        if($jarakBaseCampKeTitikJemput > 3) {
            $harga = ($jarakBaseCampKeTitikJemput - 3) * $tarifDasar->harga + ($jarakTitikJemputKeTitikTujuan * $tarifJarak->harga);
        } else {
            if($tarifJarak->id === $tarifJarak->whereJenis($tipe)->first()->id)
            {
                $harga = $tarifJarak->harga;
            } else {
                $harga = ($jarakTitikJemputKeTitikTujuan * $tarifJarak->harga);
            }
        }

        // pembulatan harga
        $harga = round($harga, -3, PHP_ROUND_HALF_UP);
    }

    if ($discount) {
        $harga -= $harga * ($discount / 100);
    }

    return $harga;
}

?>