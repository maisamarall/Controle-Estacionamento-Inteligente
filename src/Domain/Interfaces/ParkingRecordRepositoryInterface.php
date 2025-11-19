<?php
declare(strict_types=1);

namespace App\Domain\Interfaces;

use App\Domain\Entities\ParkingRecord;

interface ParkingRecordRepositoryInterface
{
    public function create(ParkingRecord $record): int;
    public function update(ParkingRecord $record): bool;
    public function findById(int $id): ?ParkingRecord;
    public function findAll(): array;
    public function findOpenByVehicleId(int $vehicleId): ?ParkingRecord;
}
