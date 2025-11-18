<?php

declare(strict_types=1);

namespace App\Domain\Interfaces;

use App\Domain\Entities\Vehicle;

interface VehicleRepositoryInterface
{
    public function getAll(): array;
    public function getById(int $id): ?Vehicle;
    public function create(Vehicle $vehicle): int;
    public function update(Vehicle $vehicle): bool;
    public function delete(int $id): bool;
}
