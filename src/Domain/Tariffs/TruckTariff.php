<?php

namespace App\Domain\Tariffs;

use App\Domain\Interfaces\TariffInterface;

class TruckTariff implements TariffInterface
{
    private float $rate = 10;

    public function calculate(int $hours): float
    {
        return $hours * $this->rate;
    }
}