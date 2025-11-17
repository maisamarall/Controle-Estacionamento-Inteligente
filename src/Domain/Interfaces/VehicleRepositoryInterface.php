<?php

interface VehicleRepositoryInterface
{
    public function getAll(): array;
    public function getById(int $id): ?Vehicle;
    public function create(Vehicle $vehicle): bool;
    public function update(Vehicle $vehicle): bool;
    public function delete(int $id): bool;
}
