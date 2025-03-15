<?php

namespace App\Http\Controllers;

use App\Helpers\OrderHelper;
use Exception;
use App\Models\Transaksi;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BantuanController extends Controller
{
    public function index()
    {
        return view('pages.bantuan-online.index');
    }

    public function pesan(Request $request)
    {
        DB::beginTransaction();

        try {
            $order_id = OrderHelper::generateOrderId('BAO-');
            Transaksi::create([
                'order_id' => $order_id,
                'jenis' => 'bantuan-online',
                'jasa' => $request->jasa,
            ]);

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil memesan jasa Bantuan Online',
                'order_id' => $order_id
            ]);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat memesan jasa Bantuan Online'
            ], 500);
        }
    }
}
