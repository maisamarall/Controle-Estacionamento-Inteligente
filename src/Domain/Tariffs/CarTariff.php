<?php

declare(strict_types=1);

namespace App\Domain\Tariffs;

use App\Domain\Interfaces\TariffInterface;

class CarTariff implements TariffInterface
{
    private float $rate = 5;

    public function calculate(int $hours): float
    {
        return $hours * $this->rate;
    }
}