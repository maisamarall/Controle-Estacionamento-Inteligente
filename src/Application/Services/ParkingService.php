<?php 

declare(strict_types=1);

namespace App\Application\Services;

use App\Domain\Entities\Vehicle;
use App\Domain\Interfaces\ParkingRepositoryInterface;

class ParkingService
{
    private ParkingRepositoryInterface $repository;

    public function __construct(ParkingRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function registerEntry(string $plate, string $type): int
    {
        $vehicle = new Vehicle($plate, $type);
        return $this->repository->registerEntry($vehicle);
    }

    public function registerLeave(int $vehicleId): void
    {
        $this->repository->registerLeave($vehicleId);
    }

    public function findVehicleById(int $id): ?Vehicle
    {
        return $this->repository->findById($id);
    }
}