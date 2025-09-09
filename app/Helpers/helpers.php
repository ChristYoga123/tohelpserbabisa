<?php 

use App\Models\TarifDasar;
use App\Models\TarifJarak;

function getPricing(string $tipe, $jarakBaseCampKeTitikJemput, $jarakTitikJemputKeTitikTujuan, $jarakBaseCampKeTitikTujuan, $jarakTitikTujuanKeTitikJemput, $discount = null)
{
    $tarifDasar = TarifDasar::whereJenis($tipe)->first();
    
    // Untuk membuat harga A ke B sama dengan B ke A, gunakan rata-rata jarak basecamp ke kedua titik
    $avgJarakBaseCampKeTitik = ($jarakBaseCampKeTitikJemput + $jarakBaseCampKeTitikTujuan) / 2;
    
    // Tarif untuk perjalanan pergi (A ke B)
    $tarifJarak = TarifJarak::whereJenis($tipe)
        ->where(function($query) use ($jarakTitikJemputKeTitikTujuan) {
            $query->where('jarak_min', '<=', $jarakTitikJemputKeTitikTujuan)
                  ->where(function($q) use ($jarakTitikJemputKeTitikTujuan) {
                      $q->where('jarak_max', '>=', $jarakTitikJemputKeTitikTujuan)
                        ->orWhereNull('jarak_max'); // Untuk handle kasus "seterusnya"
                  });
        })
        ->first();
    
    // Tarif untuk perjalanan pulang (B ke A)
    $tarifJarakKembali = TarifJarak::whereJenis($tipe)
        ->where(function($query) use ($jarakTitikTujuanKeTitikJemput) {
            $query->where('jarak_min', '<=', $jarakTitikTujuanKeTitikJemput)
                  ->where(function($q) use ($jarakTitikTujuanKeTitikJemput) {
                      $q->where('jarak_max', '>=', $jarakTitikTujuanKeTitikJemput)
                        ->orWhereNull('jarak_max'); // Untuk handle kasus "seterusnya"
                  });
        })
        ->first();
        
    $harga = 0;

    if ($tarifJarak && $tarifJarakKembali) {
        // Harga perjalanan dari titik jemput ke titik tujuan (A ke B)
        $hargaAkeB = 0;
        // Gunakan rata-rata jarak basecamp untuk membuat harga simetris
        if($avgJarakBaseCampKeTitik > 3) {
            $hargaAkeB = ($avgJarakBaseCampKeTitik - 3) * $tarifDasar->harga + ($jarakTitikJemputKeTitikTujuan * $tarifJarak->harga);
        } else {
            if($tarifJarak->id === $tarifJarak->whereJenis($tipe)->first()->id)
            {
                $hargaAkeB = $tarifJarak->harga;
            } else {
                $hargaAkeB = ($jarakTitikJemputKeTitikTujuan * $tarifJarak->harga);
            }
        }
        
        // Harga perjalanan dari titik tujuan kembali ke titik jemput (B ke A)
        $hargaBkeA = 0;
        // Gunakan rata-rata jarak basecamp untuk membuat harga simetris
        if($avgJarakBaseCampKeTitik > 3) {
            $hargaBkeA = ($avgJarakBaseCampKeTitik - 3) * $tarifDasar->harga + ($jarakTitikTujuanKeTitikJemput * $tarifJarakKembali->harga);
        } else {
            if($tarifJarakKembali->id === $tarifJarakKembali->whereJenis($tipe)->first()->id)
            {
                $hargaBkeA = $tarifJarakKembali->harga;
            } else {
                $hargaBkeA = ($jarakTitikTujuanKeTitikJemput * $tarifJarakKembali->harga);
            }
        }
        
        // Hitung rata-rata harga perjalanan pergi dan pulang
        $harga = ($hargaAkeB + $hargaBkeA) / 2;

        // pembulatan harga
        $harga = round($harga, -3, PHP_ROUND_HALF_UP);
    }

    if ($discount) {
        $harga -= $harga * ($discount / 100);
    }

    return $harga;
}

?>