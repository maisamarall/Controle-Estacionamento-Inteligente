<?php
declare(strict_types=1);

namespace App\Infra\Repositories;

use App\Domain\Entities\Vehicle;
use App\Domain\Interfaces\ParkingRepositoryInterface;
use DateTime;

class ParkingRepository implements ParkingRepositoryInterface
{
    private string $filePath;

    public function __construct(string $filePath = __DIR__ . '/../../../storage/parking.jsonl')
    {
        $this->filePath = $filePath;

        $dir = dirname($this->filePath);

        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        if (!file_exists($this->filePath)) {
            touch($this->filePath);
        }
    }

    /** 
     * @return Vehicle[]
     */

    private function readAll(): array
    {
        if (!file_exists($this->filePath)) {
            return [];
        }

        $lines = file($this->filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        return array_map(function ($line) {
            $data = json_decode($line, true);
            return $this->arrayToVehicle($data);
        }, $lines);
    }

    /**
     * @param Vehicle[] $vehicles
     */

    private function writeAll(array $vehicles): void
    {
        $lines = array_map(
            fn($v) => json_encode($this->vehicleToArray($v), JSON_UNESCAPED_UNICODE),
            $vehicles
        );

        file_put_contents($this->filePath, implode(PHP_EOL, $lines) . PHP_EOL);
    }

    public function saveEntry(Vehicle $vehicle): bool
    {
        $existing = $this->readAll();
        $lastId = empty($existing) ? 0 : end($existing)->id;

        $vehicle->id = $lastId + 1;

        $written = file_put_contents(
            $this->filePath,
            json_encode($this->vehicleToArray($vehicle), JSON_UNESCAPED_UNICODE) . PHP_EOL,
            FILE_APPEND
        );

        return $written !== false;
    }

    public function saveLeave(Vehicle $vehicle): bool
    {
        $vehicles = $this->readAll();
        $updated = false;

        $newList = array_map(function ($v) use ($vehicle, &$updated) {
            if ($v->id === $vehicle->id) {
                $updated = true;
                $v->leaveTime = $vehicle->leaveTime ?? new DateTime();
                return $v;
            }
            return $v;
        }, $vehicles);

        if (!$updated) {
            return false;
        }

        $this->writeAll($newList);
        return true;
    }

    public function findByPlate(string $plate): ?Vehicle
    {
        $records = $this->readAll();

        $matches = array_filter($records, fn($v) => $v->plate === $plate);

        if (empty($matches)) {
            return null;
        }

        return array_values($matches)[count($matches) - 1];
    }

    /**
     * @return Vehicle[]
     */

    public function listAll(): array
    {
        return $this->readAll();
    }

    private function vehicleToArray(Vehicle $v): array
    {
        return [
            'id'         => $v->id,
            'plate'      => $v->plate,
            'type'       => $v->type,
            'entryTime'  => $v->entryTime?->format('Y-m-d H:i:s'),
            'leaveTime'  => $v->leaveTime?->format('Y-m-d H:i:s'),
        ];
    }

    private function arrayToVehicle(array $data): Vehicle
    {
        $vehicle = new Vehicle($data['plate'], $data['type']);
        $vehicle->id = $data['id'];
        $vehicle->entryTime = new DateTime($data['entryTime']);

        $vehicle->leaveTime = isset($data['leaveTime']) && $data['leaveTime']
            ? new DateTime($data['leaveTime'])
            : null;

        return $vehicle;
    }
}