<?php

declare(strict_types=1);

namespace App\Infra\Repositories;

use App\Domain\Entities\Vehicle;
use App\Domain\Interfaces\ParkingRepositoryInterface;
use DateTime;
use PDO;

class ParkingRepositorySQLite implements ParkingRepositoryInterface
{
    private PDO $conn;

    public function __construct(string $dbPath = __DIR__ . '/../../../storage/parking.db')
    {
        $this->conn = new PDO("sqlite:" . $dbPath);
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $this->initializeSchema();
    }

    private function initializeSchema(): void
    {
        $sql = "
            CREATE TABLE IF NOT EXISTS vehicles (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                plate TEXT NOT NULL,
                type TEXT NOT NULL,
                entry_time TEXT NOT NULL,
                leave_time TEXT NULL
            );
        ";

        $this->conn->exec($sql);
    }

    public function saveEntry(Vehicle $vehicle): bool
    {
        $sql = "INSERT INTO vehicles (plate, type, entry_time)
                VALUES (:plate, :type, :entry_time)";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':plate' => $vehicle->plate,
            ':type' => $vehicle->type,
            ':entry_time' => $vehicle->entryTime->format('Y-m-d H:i:s'),
        ]);
    }

    public function saveLeave(Vehicle $vehicle): bool
    {
        $sql = "UPDATE vehicles
                SET leave_time = :leave_time
                WHERE id = :id";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':leave_time' => $vehicle->leaveTime?->format('Y-m-d H:i:s'),
            ':id' => $vehicle->id,
        ]);
    }

    public function findByPlate(string $plate): ?Vehicle
    {
        $sql = "SELECT * FROM vehicles
                WHERE plate = :plate
                ORDER BY id DESC
                LIMIT 1";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':plate' => $plate]);

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data) {
            return null;
        }

        return $this->mapToVehicle($data);
    }

    public function listAll(): array
    {
        $sql = "SELECT * FROM vehicles ORDER BY entry_time DESC";
        $stmt = $this->conn->query($sql);

        $vehicles = [];

        while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $vehicles[] = $this->mapToVehicle($data);
        }

        return $vehicles;
    }

    private function mapToVehicle(array $data): Vehicle
    {
        $vehicle = new Vehicle($data['plate'], $data['type']);
        $vehicle->id = (int)$data['id'];
        $vehicle->entryTime = new DateTime($data['entry_time']);
        $vehicle->leaveTime = $data['leave_time']
            ? new DateTime($data['leave_time'])
            : null;

        return $vehicle;
    }
}