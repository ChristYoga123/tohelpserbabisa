<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Voucher;
use App\Models\Transaksi;
use App\Models\TarifDasar;
use App\Models\TarifJarak;
use Illuminate\Support\Str;
use App\Helpers\OrderHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OjekController extends Controller
{
    public function index()
    {
        return view('pages.transportasi.index', [
            'tarifDasar' => TarifDasar::whereJenis('Motor')->first(),
            'tarifJarak' => TarifJarak::whereJenis('Motor')->first(),
        ]);
    }

    public function showPricing(Request $request)
    {
        $discount = null;
        if($request->discount)
        {
            $discount = Voucher::where('nama', $request->discount)->first();

            if(!$discount)
            {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Voucher tidak ditemukan'
                ], 404);
            }
        }
        $harga = getPricing('Motor', $request->jarakBaseCampKeTitikJemput, $request->jarakTitikJemputKeTitikTujuan, $discount->persentase ?? null);

        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil mendapatkan harga',
            'harga' => $harga,
        ]);
    }

    public function pesan(Request $request)
    {
        DB::beginTransaction();

        try {
            $data = [
                'order_id' => OrderHelper::generateOrderId('OJK-'),
                'jenis' => 'ojek',
                'voucher_id' => Voucher::whereNama($request->voucher)->first()->id ?? null,
                'jarak' => $request->jarak,
                'titik_jemput' => $request->titik_jemput,
                'titik_tujuan' => $request->titik_tujuan,
                'total_harga' => getPricing('Motor',  $request->jarakBaseCampKeTitikJemput, $request->jarak, Voucher::whereNama($request->voucher)->first()->persentase ?? null),
            ];
            Transaksi::create($data);

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil memesan jasa ojek',
                'order_id' => $data['order_id'],
                'harga' => $data['total_harga'],
            ]);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat memesan jasa ojek'
            ], 500);
        }
    }
}
