<?php

namespace App\Http\Controllers;

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

        try
        {
            Transaksi::create([
                'order_id' => 'B-' . Str::random(8),
                'jenis' => 'bersih-rumah',
                'jasa' => $request->jasa,
                'total_harga' => $request->total_harga,
            ]);

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil memesan jasa bersih-bersih'
            ]);
        }catch(Exception $e)
        {
            Log::error($e->getMessage());
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat memesan jasa bersih-bersih'
            ], 500);
        }
    }
}
