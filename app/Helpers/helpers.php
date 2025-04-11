<?php 

use App\Models\TarifDasar;
use App\Models\TarifJarak;

function getPricing(string $tipe, $jarakBaseCampKeTitikJemput, $jarakTitikJemputKeTitikTujuan)
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
        if($jarakBaseCampKeTitikJemput > 5) {
            $harga = ($jarakBaseCampKeTitikJemput - 5) * $tarifDasar->harga + ($jarakTitikJemputKeTitikTujuan * $tarifJarak->harga);
        } else {
            $harga = ($jarakTitikJemputKeTitikTujuan * $tarifJarak->harga);
        }

        // pembulatan harga
        $harga = round($harga, -3, PHP_ROUND_HALF_UP);
    }

    return $harga;
}

?>