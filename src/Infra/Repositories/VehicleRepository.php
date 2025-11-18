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

    public function create(Vehicle $vehicle): bool
    {
        $sql = "INSERT INTO vehicles (plate, type, entry_time, leave_time) 
                VALUES (:plate, :type, :entry_time, :leave_time)";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(':plate', $vehicle->getPlate());
        $stmt->bindValue(':type', $vehicle->getType());
        $stmt->bindValue(':entry_time', $vehicle->entryTime->format('Y-m-d H:i:s'));
        $stmt->bindValue(':leave_time', $vehicle->leaveTime ? $vehicle->leaveTime->format('Y-m-d H:i:s') : null);

        return $stmt->execute();
    }

    public function getAll(): array
    {
        $sql = "SELECT * FROM vehicles ORDER BY id DESC";
        $stmt = $this->conn->query($sql);

        $vehicles = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $vehicle = new Vehicle($row['plate'], $row['type']);
            $vehicle->id = $row['id'];
            $vehicle->entryTime = new DateTime($row['entry_time']);
            $vehicle->leaveTime = $row['leave_time'] ? new DateTime($row['leave_time']) : null;

            $vehicles[] = $vehicle;
        }

        return $vehicles;
    }

    public function getById(int $id): ?Vehicle
    {
        $sql = "SELECT * FROM vehicles WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":id", $id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        $vehicle = new Vehicle($row['plate'], $row['type']);
        $vehicle->id = $row['id'];
        $vehicle->entryTime = new DateTime($row['entry_time']);
        $vehicle->leaveTime = $row['leave_time'] ? new DateTime($row['leave_time']) : null;

        return $vehicle;
    }

    public function update(Vehicle $vehicle): bool
    {
        $sql = "UPDATE vehicles 
                SET plate = :plate,
                    type = :type,
                    entry_time = :entry_time,
                    leave_time = :leave_time
                WHERE id = :id";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":plate", $vehicle->plate);
        $stmt->bindValue(":type", $vehicle->type);
        $stmt->bindValue(":entry_time", $vehicle->entryTime->format('Y-m-d H:i:s'));
        $stmt->bindValue(":leave_time", $vehicle->leaveTime ? $vehicle->leaveTime->format('Y-m-d H:i:s') : null);
        $stmt->bindValue(":id", $vehicle->id);

        return $stmt->execute();
    }

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM vehicles WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":id", $id);
        return $stmt->execute();
    }
}
