<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use Carbon\Carbon;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    public function check(Request $request)
    {
        $voucher = Voucher::whereNama($request->code)->first();

        if($voucher)
        {
            if(Carbon::now()->lessThan($voucher->tanggal_berakhir))
            {
                return response()->json([
                    'status' => 'success',
                    'data' => $voucher
                ]);
            }
            return response()->json([
                'status' => 'error',
                'message' => 'Voucher sudah tidak berlaku'
            ], 400);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Voucher tidak ditemukan'
        ], 404);
    }
}
