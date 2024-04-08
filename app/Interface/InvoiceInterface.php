<?php

namespace App\Interface;

interface InvoiceInterface
{
    function internalOrder(string $status,string $available);

    function externalOrder(string $status,string $available);
}
