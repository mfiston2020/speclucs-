<?php

namespace App\Interface;

interface SalesInterface{

    function addProductToInvoice(array $request);

    function createInvoice(array $productType);

    function saveProductOrder(string $invoice,array $product);
}
