<?php

declare(strict_types=1);

namespace App\Infra\Repositories;

use PDO;
use App\Domain\Entities\Vehicle;
use App\Domain\Interfaces\VehicleRepositoryInterface;

class VehicleRepositorySQLite implements VehicleRepositoryInterface
{
    private PDO $db;

    public function __construct(PDO $connection)
    {
        $this->db = $connection;
    }

    public function getAll(): array
    {
        $stmt = $this->db->query("SELECT * FROM vehicles");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $vehicles = [];
        foreach ($rows as $row) {
            $vehicle = new Vehicle($row['plate'], $row['type']);
            $vehicle->id = (int)$row['id'];
            $vehicle->entryTime = new \DateTime($row['entry_time']);
            $vehicle->leaveTime = $row['leave_time'] ? new \DateTime($row['leave_time']) : null;


            $vehicles[] = $vehicle;
        }

        return $vehicles;
    }

    public function getById(int $id): ?Vehicle
    {
        $stmt = $this->db->prepare("SELECT * FROM vehicles WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        $vehicle = new Vehicle($row['plate'], $row['type']);
        $vehicle->setId((int)$row['id']);
        $vehicle->entryTime = new \DateTime($row['entry_time']);
        $vehicle->leaveTime = $row['leave_time'] ? new \DateTime($row['leave_time']) : null;

        return $vehicle;
    }

    public function create(Vehicle $vehicle): int
    {
        $stmt = $this->db->prepare("
            INSERT INTO vehicles (plate, type, entry_time, leave_time)
            VALUES (?, ?, ?, ?)
        ");

        $stmt->execute([
            $vehicle->getPlate(),
            $vehicle->getType(),
            $vehicle->getEntryTime()->format('Y-m-d H:i:s'),
            $vehicle->getLeaveTime()?->format('Y-m-d H:i:s')
        ]);

        return (int)$this->db->lastInsertId();

    }

    public function update(Vehicle $vehicle): bool
    {
        $stmt = $this->db->prepare("
            UPDATE vehicles
            SET plate = ?, type = ?, entry_time = ?, leave_time = ?
            WHERE id = ?
        ");

        return $stmt->execute([
            $vehicle->getPlate(),
            $vehicle->getType(),
            $vehicle->getEntryTime()->format('Y-m-d H:i:s'),
            $vehicle->getLeaveTime()?->format('Y-m-d H:i:s'),
            $vehicle->getId()
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM vehicles WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
