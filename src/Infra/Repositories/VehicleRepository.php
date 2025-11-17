<?php
// src/Infra/Repositories/VehicleRepository.php

require_once __DIR__ . '/../Database/MySQLConnection.php';
require_once __DIR__ . '/../../Domain/Entities/Vehicle.php';
require_once __DIR__ . '/../../Domain/Interfaces/VehicleRepositoryInterface.php';

class VehicleRepository implements VehicleRepositoryInterface
{
    private PDO $conn;

    public function __construct()
    {
        $this->conn = MySQLConnection::getConnection();
    }

    public function save(Vehicle $vehicle): int
    {
        $sql = "INSERT INTO vehicles (plate, type, entry_time, leave_time) VALUES (:plate, :type, :entry_time, :leave_time)";
        $stmt = $this->conn->prepare($sql);

        $entry = $vehicle->getEntryTime()->format('Y-m-d H:i:s');
        $leave = $vehicle->getLeaveTime() ? $vehicle->getLeaveTime()->format('Y-m-d H:i:s') : null;

        $stmt->bindValue(':plate', $vehicle->getPlate());
        $stmt->bindValue(':type', $vehicle->getType());
        $stmt->bindValue(':entry_time', $entry);
        $stmt->bindValue(':leave_time', $leave);

        $stmt->execute();
        return intval($this->conn->lastInsertId());
    }

    public function update(Vehicle $vehicle): void
    {
        $sql = "UPDATE vehicles SET plate = :plate, type = :type, entry_time = :entry_time, leave_time = :leave_time WHERE id = :id";
        $stmt = $this->conn->prepare($sql);

        $entry = $vehicle->getEntryTime()->format('Y-m-d H:i:s');
        $leave = $vehicle->getLeaveTime() ? $vehicle->getLeaveTime()->format('Y-m-d H:i:s') : null;

        $stmt->bindValue(':plate', $vehicle->getPlate());
        $stmt->bindValue(':type', $vehicle->getType());
        $stmt->bindValue(':entry_time', $entry);
        $stmt->bindValue(':leave_time', $leave);
        $stmt->bindValue(':id', $vehicle->getId());

        $stmt->execute();
    }

    public function findByPlate(string $plate): ?Vehicle
    {
        $sql = "SELECT * FROM vehicles WHERE plate = :plate ORDER BY id DESC LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':plate', $plate);
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

    public function listAll(): array
    {
        $sql = "SELECT * FROM vehicles ORDER BY entry_time DESC";
        $stmt = $this->conn->query($sql);

        $vehicles = [];

        while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $vehicle = new Vehicle($data['plate'], $data['type']);
            $vehicle->setId($data['id']);
            $vehicle->entryTime = new DateTime($data['entry_time']);
            $vehicle->leaveTime = $data['leave_time'] ? new DateTime($data['leave_time']) : null;
            $vehicles[] = $vehicle;
        }

        return $vehicles;
    }
}