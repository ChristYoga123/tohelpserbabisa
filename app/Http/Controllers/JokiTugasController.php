<?php

namespace App\Http\Controllers;

use App\Helpers\OrderHelper;
use App\Models\Transaksi;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class JokiTugasController extends Controller
{
    public function index()
    {
        return view('pages.joki-tugas.index');
    }

    public function pesan(Request $request)
    {
        DB::beginTransaction();

        try {
            $data = [
                'order_id' => OrderHelper::generateOrderId('JOT-'),
                'jenis' => 'joki-tugas',
                'jasa' => $request->jasa,
            ];
            if ($request->has('total_harga')) {
                $data['total_harga'] = $request->total_harga;
            }

            Transaksi::create($data);

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil memesan jasa joki tugas',
                'order_id' => $data['order_id']
            ]);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat memesan jasa joki tugas'
            ], 500);
        }
    }
}
