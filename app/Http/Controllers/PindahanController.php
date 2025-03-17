<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Transaksi;
use Illuminate\Support\Str;
use App\Helpers\OrderHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PindahanController extends Controller
{
    public function index()
    {
        return view('pages.pindahan.index');
    }

    public function pesan(Request $request)
    {
        DB::beginTransaction();

        try {
            $data = [
                'order_id' => OrderHelper::generateOrderId('P-'),
                'jenis' => 'angkutan',
                'jasa' => $request->jasa,
                'total_harga' => $request->total_harga,
            ];
            Transaksi::create($data);

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil memesan jasa pindahan',
                'order_id' => $data['order_id']
            ]);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat memesan jasa pindahan'
            ], 500);
        }
    }
}
