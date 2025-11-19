<?php

namespace App\Domain\Interfaces;

interface TariffInterface
{
    public function calculate(int $hours): float;
}