<?php
declare(strict_types=1);

namespace App\Domain;

use App\Domain\ValueObjects\VehicleType;

class TariffFactory
{
    public static function getRate(string $type): float
    {
        return match ($type) {
            VehicleType::carro => 5.0,
            VehicleType::moto => 3.0,
            VehicleType::caminhao => 10.0,
            default => throw new \InvalidArgumentException("Tipo de veículo inválido: {$type}")
        };
    }
}
