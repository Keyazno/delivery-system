<?php

namespace App\Services;

class IdGeneratorService
{
    public function generateClientId()
    {
        return strtoupper('CL-' . bin2hex(random_bytes(3)));
    }

    public function generateDriverId()
    {
        return strtoupper('DRV-' . bin2hex(random_bytes(2)));
    }

    public function generateOrderId()
    {
        return 'ORD-' . now()->format('YmdHi');
    }
}
