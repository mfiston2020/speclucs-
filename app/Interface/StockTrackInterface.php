<?php

namespace App\Interface;

interface StockTrackInterface
{
    function saveTrackRecord(string $productId, string $currentStock, string $incoming, string $change, string $reason, string $type, string $operation);
}
