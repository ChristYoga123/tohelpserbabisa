<?php

namespace App\Helpers;

use App\Models\Transaksi;
use App\Models\TarifDasar;
use App\Models\TarifJarak;
use InvalidArgumentException;

class OrderHelper
{
    public static function generateOrderId($prefix)
    {
        if (empty($prefix)) {
            throw new InvalidArgumentException('Prefix is required.');
        }

        if (!is_string($prefix)) {
            throw new InvalidArgumentException('Prefix must be a string.');
        }

        $lastOrder = Transaksi::where('order_id', 'like', $prefix . date('ymd') . '%')
            ->orderBy('order_id', 'desc')
            ->first();

        $sequence = 1;
        if ($lastOrder) {
            $lastSequence = (int)substr($lastOrder->order_id, -3);
            $sequence = $lastSequence + 1;
        }

        return $prefix . date('ymd') . str_pad($sequence, 3, '0', STR_PAD_LEFT);
    }
}
