<?php

namespace App\Interface;

interface InvoiceInterface
{
    function internalOrder(array $status,string $available);

    function externalOrder(array $status,string $available);
}
