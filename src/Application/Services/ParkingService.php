<?php
declare(strict_types=1);

namespace App\Application\Services;

use App\Domain\Entities\Vehicle;
use App\Domain\Interfaces\ParkingRepositoryInterface;
use App\Domain\TariffFactory;
use DateTime;

class ParkingService
{
    private ParkingRepositoryInterface $repository;

    public function __construct(ParkingRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function registerEntry(string $plate, string $type): bool
    {
        $vehicle = new Vehicle($plate, $type);
        return $this->repository->saveEntry($vehicle);
    }

    public function registerExit(string $plate): ?float
    {
        $vehicle = $this->repository->findByPlate($plate);

        if (!$vehicle) {
            return null;
        }

        $vehicle->leaveTime = new DateTime();

        $hours = $this->calculateHours($vehicle->entryTime, $vehicle->leaveTime);

        // Usa a factory corretamente
        $tariff = TariffFactory::create($vehicle->type);
        $price = $tariff->calculate($hours);

        $this->repository->saveLeave($vehicle);

        return $price;
    }

    public function listAll(): array
    {
        return $this->repository->listAll();
    }

    // -------------------------------
    // RESUMO POR TIPO ✔️
    // -------------------------------
    public function summaryByType(): array
    {
        $vehicles = $this->repository->listAll();

        $summary = [
            'carro' => ['count' => 0, 'revenue' => 0],
            'moto' => ['count' => 0, 'revenue' => 0],
            'caminhao' => ['count' => 0, 'revenue' => 0],
        ];

        foreach ($vehicles as $v) {
            $type = strtolower($v->type);

            if (!isset($summary[$type])) {
                continue;
            }

            // conta
            $summary[$type]['count']++;

            // sem saída = não conta faturamento
            if (empty($v->leaveTime)) {
                continue;
            }

            // horas
            $hours = $this->calculateHours($v->entryTime, $v->leaveTime);

            // tarifa pelo tipo
            $tariff = TariffFactory::create($type);

            // soma ao faturamento
            $summary[$type]['revenue'] += $tariff->calculate($hours);
        }

        return $summary;
    }

    private function calculateHours(DateTime $start, DateTime $end): int
    {
        $diff = $start->diff($end);
        $hours = ($diff->days * 24) + $diff->h + ($diff->i > 0 ? 1 : 0);
        return max($hours, 1);
    }
}
