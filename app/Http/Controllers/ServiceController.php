<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Transaksi;
use App\Helpers\OrderHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ServiceController extends Controller
{
    public function index()
    {
        return view('pages.service.index');
    }

    public function pesan(Request $request)
    {
        DB::beginTransaction();

        try {
            $data = [
                'order_id' => OrderHelper::generateOrderId('SER-'),
                'jenis' => 'all-service',
                'jasa' => $request->jasa,
            ];

            Transaksi::create($data);

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil memesan jasa antar service',
                'order_id' => $data['order_id']
            ]);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat memesan jasa antar service'
            ], 500);
        }
    }
}
