<?php

namespace App\Domain;

use App\Domain\Tariffs\CarTariff;
use App\Domain\Tariffs\MotorcycleTariff;
use App\Domain\Tariffs\TruckTariff;

class TariffFactory
{
    public static function create(string $vehicleType)
    {
        return match (strtolower($vehicleType)) {
            'car',
            'carro' => new CarTariff(),

            'motorcycle',
            'moto',
            'motocicleta' => new MotorcycleTariff(),

            'truck',
            'caminhao' => new TruckTariff(),

            default => throw new \Exception("Tipo de veículo inválido: $vehicleType"),
        };
    }
}
