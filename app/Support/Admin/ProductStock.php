<?php

namespace App\Support\Admin;

use App\Models\Inventory;

class ProductStock
{

    public static function productStockCount(int $productId): array
    {
        $totals = Inventory::query()
            ->where('product_id', $productId)
            ->where('status', 'active')
            ->selectRaw('
            SUM(initial_quantity)   as initial_quantity,
            SUM(reserved_quantity)  as reserved_quantity,
            SUM(remaining_quantity) as remaining_quantity
        ')
            ->first();

        return [
            'initial_quantity' => (int)$totals->initial_quantity,
            'reserved_quantity' => (int)$totals->reserved_quantity,
            'remaining_quantity' => (int)$totals->remaining_quantity,
        ];
    }


}
