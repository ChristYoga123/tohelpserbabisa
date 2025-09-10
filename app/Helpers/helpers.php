<?php 

use App\Models\TarifDasar;
use App\Models\TarifJarak;

function getPricing(string $tipe, $jarakBaseCampKeTitikJemput, $jarakTitikJemputKeTitikTujuan, $jarakBaseCampKeTitikTujuan, $jarakTitikTujuanKeTitikJemput, $discount = null)
{
    $tarifDasar = TarifDasar::whereJenis($tipe)->first();
    
    // Ambil range pertama (jarak terdekat) dari database secara dinamis
    $firstRange = TarifJarak::whereJenis($tipe)
        ->orderBy('jarak_min', 'asc')
        ->first();
    
    $firstRangeMax = $firstRange->jarak_max ?? $firstRange->jarak_min + 3; // fallback jika null
    
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
        // Harga perjalanan dari titik tujuan kembali ke titik jemput (B ke A)
        $hargaBkeA = 0;
        // Gunakan rata-rata jarak basecamp untuk membuat harga simetris
        if($avgJarakBaseCampKeTitik > 3) {
            $hargaAkeB = ($avgJarakBaseCampKeTitik - 3) * $tarifDasar->harga;
            $hargaBkeA = ($avgJarakBaseCampKeTitik - 3) * $tarifDasar->harga;
        }

        // Hitung harga berdasarkan jarak
        $hargaAkeB +=  ($jarakTitikJemputKeTitikTujuan > $firstRangeMax) ? ($jarakTitikJemputKeTitikTujuan * $tarifJarak->harga) : $tarifJarak->harga;
        $hargaBkeA +=  ($jarakTitikTujuanKeTitikJemput > $firstRangeMax) ? ($jarakTitikTujuanKeTitikJemput * $tarifJarakKembali->harga) : $tarifJarakKembali->harga;
        
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