<?php

namespace App\Interface;

interface ProductInterface{
    function searchProduct(string $lensType,string $index,string $chromatic,string $coating,string $sphere,string $cylinder,string $eye,string $axis,string $add);

    function saveProduct(array $request);
}
