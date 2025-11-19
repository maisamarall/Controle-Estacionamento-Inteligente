<?php

declare(strict_types=1);

namespace App\Domain\Tariffs;

use App\Domain\Interfaces\TariffInterface;

class MotorcycleTariff implements TariffInterface
{
    private float $rate = 3;

    public function calculate(int $hours): float
    {
        return $hours * $this->rate;
    }
}