<?php

namespace App\Interface;

interface ProductInterface
{
    function productMatrix(array $request);

    function sellPendingOrder(array $request);

    function saveUnavailableToStock(array $product);

    function searchProduct(array $productDescription);

    function addUnavailableProduct(array $productInfo);

    function productStockEfficiency(string $product_id,int $usag,int $stoc,int $cat,int $days=null);

    function makeLabOrder(array $request, string $product_id);

    function searchUnavailableProduct(array $productDescription);

    function saveProduct(array $request, string $category, array $pending, bool $isOrder);
}
