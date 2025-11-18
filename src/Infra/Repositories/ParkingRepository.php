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

    public function saveEntry(Vehicle $vehicle): bool
    {
        $sql = "INSERT INTO vehicles (plate, type, entry_time)
                VALUES (:plate, :type, :entry_time)";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(':plate', $vehicle->getPlate());
        $stmt->bindValue(':type', $vehicle->getType());
        $stmt->bindValue(':entry_time', $vehicle->entryTime->format('Y-m-d H:i:s'));

        return $stmt->execute();
    }

    public function saveLeave(Vehicle $vehicle): bool
    {
        $sql = "UPDATE vehicles 
                SET leave_time = :leave_time
                WHERE id = :id";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(':leave_time', $vehicle->leaveTime
            ? $vehicle->leaveTime->format('Y-m-d H:i:s')
            : (new DateTime())->format('Y-m-d H:i:s')
        );

        $stmt->bindValue(':id', $vehicle->id);

        return $stmt->execute();
    }

    public function findByPlate(string $plate): ?Vehicle
    {
        $sql = "SELECT * FROM vehicles 
                WHERE plate = :plate 
                ORDER BY id DESC 
                LIMIT 1";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':plate', $plate);
        $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data) {
            return null;
        }

        $vehicle = new Vehicle($data['plate'], $data['type']);
        $vehicle->id = $data['id'];
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
            $vehicle->id = $data['id'];
            $vehicle->entryTime = new DateTime($data['entry_time']);
            $vehicle->leaveTime = $data['leave_time'] ? new DateTime($data['leave_time']) : null;

            $vehicles[] = $vehicle;
        }

        return $vehicles;
    }
}
