<?php

namespace App\Interface;

interface StockTrackInterface
{
    function saveTrackRecord(string $productId, string $currentStock, string $incoming, string $change, string $reason, string $type, string $operation);

    function saveTrackPricing(string $productId,string $currentPrice,string $incoming,string $status,string $side);
}
