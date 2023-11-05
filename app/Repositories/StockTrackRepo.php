<?php

namespace App\Repositories;

use App\Interface\StockTrackInterface;
use App\Models\Product;
use App\Models\TrackStockRecord;

class StockTrackRepo implements StockTrackInterface
{

    public String $message  =   '';
    public bool $showProductDetails  =   false;
    public $products;


    // public function __construct()
    // {
    //     $this->products = Product::where('');
    // }

    function saveTrackRecord(string $productId, string $currentStock, string $incoming, string $change, string $reason, string $type, string $operation)
    {
        TrackStockRecord::create([
            'product_id'    => $productId,
            'user_id'       => userInfo()->id,
            'company_id'    => userInfo()->company_id,
            'current_stock' => $currentStock,
            'incoming'      => $incoming,
            'change'        => $operation == 'in' ? (int)$currentStock + (int)$incoming : (int)$currentStock - (int)$incoming,
            'operation'     => $operation,
            'reason'        => $reason,
            'type'          => $type,
            'status'        => $operation,
        ]);
    }
}
