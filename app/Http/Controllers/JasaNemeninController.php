<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Transaksi;
use App\Helpers\OrderHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class JasaNemeninController extends Controller
{
    public function index()
    {
        return view('pages.jasa-nemenin.index');
    }

    public function pesan(Request $request)
    {
        DB::beginTransaction();

        try {
            $data = [
                'order_id' => OrderHelper::generateOrderId('JSN-'),
                'jenis' => 'jasa-nemenin',
                'jasa' => $request->jasa,
            ];

            Transaksi::create($data);

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil memesan jasa nemenin',
                'order_id' => $data['order_id']
            ]);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat memesan jasa nemenin'
            ], 500);
        }
    }
}
