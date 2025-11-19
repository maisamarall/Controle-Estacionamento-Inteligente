<?php
declare(strict_types=1);

namespace App\Domain\Interfaces;

use App\Domain\Entities\Vehicle;

interface ParkingRepositoryInterface
{
    public function saveEntry(Vehicle $vehicle): bool;
    public function saveLeave(Vehicle $vehicle): bool;
    public function findByPlate(string $plate): ?Vehicle;
    public function listAll(): array;
}