<?php

namespace App\Http\Controllers;

use App\Helpers\OrderHelper;
use Exception;
use App\Models\Transaksi;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BersihController extends Controller
{
    public function index()
    {
        return view('pages.bersih.index');
    }

    public function pesan(Request $request)
    {
        DB::beginTransaction();

        try {
            $data = [
                'order_id' => OrderHelper::generateOrderId('B-'),
                'jenis' => 'bersih-rumah',
                'jasa' => $request->jasa,
            ];

            if ($request->has('total_harga')) {
                $data['total_harga'] = $request->total_harga;
            }

            Transaksi::create($data);

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil memesan jasa bersih-bersih',
                'order_id' => $data['order_id']
            ]);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat memesan jasa bersih-bersih'
            ], 500);
        }
    }
}
