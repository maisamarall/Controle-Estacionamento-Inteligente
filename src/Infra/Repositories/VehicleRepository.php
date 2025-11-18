<?php

declare(strict_types=1);

namespace App\Infra\Repositories;

use App\Domain\Entities\Vehicle;
use App\Domain\Interfaces\VehicleRepositoryInterface;

class VehicleRepository implements VehicleRepositoryInterface
{
    private string $filePath;

    public function __construct(string $filePath = __DIR__ . '/../../../storage/vehicles.jsonl')
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

    public function getAll(): array
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

    public function getById(int $id): ?Vehicle
    {
        foreach ($this->getAll() as $vehicle) {
            if ($vehicle->id === $id) {
                return $vehicle;
            }
        }

        return null;
    }

    public function create(Vehicle $vehicle): int
    {
        $existing = $this->getAll();
        $lastId = empty($existing) ? 0 : end($existing)->id;

        $vehicle->id = $lastId + 1;

        file_put_contents(
            $this->filePath,
            json_encode($this->vehicleToArray($vehicle), JSON_UNESCAPED_UNICODE) . PHP_EOL,
            FILE_APPEND
        );

        return $vehicle->id;
    }

    public function update(Vehicle $vehicle): bool
    {
        $vehicles = $this->getAll();
        $updated = false;

        $newList = array_map(function ($v) use ($vehicle, &$updated) {
            if ($v->id === $vehicle->id) {
                $updated = true;
                return $vehicle;
            }
            return $v;
        }, $vehicles);

        if (!$updated) {
            return false;
        }

        $this->writeAll($newList);
        return true;
    }

    public function delete(int $id): bool
    {
        $vehicles = $this->getAll();
        $filtered = array_filter($vehicles, fn($v) => $v->id !== $id);

        if (count($filtered) === count($vehicles)) {
            return false;
        }

        $this->writeAll(array_values($filtered));
        return true;
    }

    private function vehicleToArray(Vehicle $v): array
    {
        return [
            'id' => $v->id,
            'plate' => $v->plate,
            'type' => $v->type,
            'entryTime' => $v->entryTime?->format('Y-m-d H:i:s'),
            'leaveTime' => $v->leaveTime?->format('Y-m-d H:i:s'),
        ];
    }

    private function arrayToVehicle(array $data): Vehicle
    {
        $vehicle = new Vehicle($data['plate'], $data['type']);
        $vehicle->id = $data['id'];
        $vehicle->entryTime = new \DateTime($data['entryTime']);

        $vehicle->leaveTime = isset($data['leaveTime']) && $data['leaveTime']
            ? new \DateTime($data['leaveTime'])
            : null;

        return $vehicle;
    }

    private function writeAll(array $vehicles): void
    {
        $lines = array_map(
            fn($v) => json_encode($this->vehicleToArray($v), JSON_UNESCAPED_UNICODE),
            $vehicles
        );

        file_put_contents($this->filePath, implode(PHP_EOL, $lines) . PHP_EOL);
    }
}
