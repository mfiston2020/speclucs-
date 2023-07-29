<?php

namespace App\Interface;

interface ProductInterface
{
    function addUnavailableProduct(array $productInfo);

    function productMatrix(array $request);

    function sellPendingOrder(array $request);

    function searchProduct(array $productDescription);

    function makeLabOrder(array $request, string $product_id);

    function saveProduct(array $request, string $category, array $pending, bool $isOrder);
}
