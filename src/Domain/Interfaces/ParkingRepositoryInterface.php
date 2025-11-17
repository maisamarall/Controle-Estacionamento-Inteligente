<?php

interface ParkingRepositoryInterface
{
    public function saveEntry(Vehicle $vehicle): bool;
    public function saveLeave(Vehicle $vehicle): bool;
    public function findByPlate(string $plate): ?Vehicle;
    public function listAll(): array;
}
