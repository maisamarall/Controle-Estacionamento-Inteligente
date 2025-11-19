<?php 

declare(strict_types=1);

namespace App\Application\Services;

use App\Domain\Interfaces\ParkingRepositoryInterface;

class ReportService
{
    private ParkingRepositoryInterface $repository;

    public function __construct(ParkingRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function generateDailyReport(): array
    {
        $activeVehicles = $this->repository->listAll();
        $report = [];

        foreach ($activeVehicles as $vehicle) {
            $report[] = [
                'id' => $vehicle->getId(),
                'plate' => $vehicle->getPlate(),
                'type' => $vehicle->getType(),
                'entry_time' => $vehicle->getEntryTime()->format('Y-m-d H:i:s'),
            ];
        }

        return $report;
    }
}