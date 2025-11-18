<?php 

declare(strict_types=1);

namespace App\Application\Services;

use App\Domain\Entities\Vehicle;
use App\Domain\Interfaces\ParkingRepositoryInterface;
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
        $vehicle->entryTime = new DateTime();

        return $this->repository->saveEntry($vehicle);
    }

    public function registerLeave(string $plate): bool
    {
        $vehicle = $this->repository->findByPlate($plate);

        if (!$vehicle) {
            return false;
        }

        $vehicle->leaveTime = new DateTime();

        return $this->repository->saveLeave($vehicle);
    }

    public function findByPlate(string $plate): ?Vehicle
    {
        return $this->repository->findByPlate($plate);
    }

    public function listAll(): array
    {
        return $this->repository->listAll();
    }
}