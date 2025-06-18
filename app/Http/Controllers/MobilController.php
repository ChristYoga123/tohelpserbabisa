<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Cabang;
use App\Models\Voucher;
use App\Models\Transaksi;
use App\Models\TarifDasar;
use App\Models\TarifJarak;
use Illuminate\Support\Str;
use App\Helpers\OrderHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MobilController extends Controller
{
    public function index()
    {
        return view('pages.taxi.index', [
            'tarifDasar' => TarifDasar::whereJenis('Mobil')->first(),
            'tarifJarak' => TarifJarak::whereJenis('Mobil')->first(),
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
        $harga = getPricing('Mobil', $request->jarakBaseCampKeTitikJemput, $request->jarakTitikJemputKeTitikTujuan, $discount->persentase ?? null);

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
            // Get cabang based on the provided cabang_id
            $cabang = Cabang::find($request->cabang);
            
            if (!$cabang) {
                throw new Exception('Cabang tidak ditemukan');
            }

            $data = [
                'order_id' =>  OrderHelper::generateOrderId('TX-'),
                'jenis' => 'taxi',
                'voucher_id' => Voucher::whereNama($request->voucher)->first()->id ?? null,
                'jarak' => $request->jarak,
                'titik_jemput' => $request->titik_jemput,
                'titik_tujuan' => $request->titik_tujuan,
                'total_harga' => getPricing('Mobil',  $request->jarakBaseCampKeTitikJemput, $request->jarak, Voucher::whereNama($request->voucher)->first()->persentase ?? null),
                'cabang_id' => $cabang->id ?? Cabang::first()->id,
            ];
            Transaksi::create($data);

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil memesan jasa taxi',
                'order_id' => $data['order_id'],
                'harga' => $data['total_harga'],
            ]);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat memesan jasa taxi'
            ], 500);
        }
    }
}
