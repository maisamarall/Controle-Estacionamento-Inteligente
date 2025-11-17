<?php
// src/Infra/Repositories/ParkingRepository.php

require_once __DIR__ . '/../Database/MySQLConnection.php';
require_once __DIR__ . '/../../Domain/Entities/Vehicle.php';
require_once __DIR__ . '/../../Domain/Interfaces/ParkingRepositoryInterface.php';


class ParkingRepository implements ParkingRepositoryInterface
{
    private PDO $conn;

    public function __construct()
    {
        $this->conn = MySQLConnection::getConnection();
    }

    public function registerEntry(Vehicle $vehicle): int
    {
        $sql = "INSERT INTO vehicles (plate, type, entry_time) VALUES (:plate, :type, :entry_time)";
        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(':plate', $vehicle->getPlate());
        $stmt->bindValue(':type', $vehicle->getType());
        $stmt->bindValue(':entry_time', $vehicle->getEntryTime()->format('Y-m-d H:i:s'));

        $stmt->execute();
        return intval($this->conn->lastInsertId());
    }

    public function registerLeave(int $vehicleId): void
    {
        $sql = "UPDATE vehicles SET leave_time = :leave_time WHERE id = :id";
        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(':leave_time', (new DateTime())->format('Y-m-d H:i:s'));
        $stmt->bindValue(':id', $vehicleId);

        $stmt->execute();
    }

    public function findById(int $id): ?Vehicle
    {
        $sql = "SELECT * FROM vehicles WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data) {
            return null;
        }

        $vehicle = new Vehicle($data['plate'], $data['type']);
        $vehicle->setId($data['id']);
        $vehicle->entryTime = new DateTime($data['entry_time']);
        $vehicle->leaveTime = $data['leave_time'] ? new DateTime($data['leave_time']) : null;

        return $vehicle;
    }

    public function listActive(): array
    {
        $sql = "SELECT * FROM vehicles WHERE leave_time IS NULL";
        $stmt = $this->conn->query($sql);

        $vehicles = [];

        while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $vehicle = new Vehicle($data['plate'], $data['type']);
            $vehicle->setId($data['id']);
            $vehicle->entryTime = new DateTime($data['entry_time']);
            $vehicle->leaveTime = null;
            $vehicles[] = $vehicle;
        }

        return $vehicles;
    }
}