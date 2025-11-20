<?php
declare(strict_types=1);

namespace App\Infra\Repositories;

use App\Domain\Entities\ParkingRecord;
use App\Domain\Interfaces\ParkingRecordRepositoryInterface;
use DateTime;

final class ParkingRecordFileRepository implements ParkingRecordRepositoryInterface
{
    private string $filePath;

    public function __construct(string $filePath = __DIR__ . '/../../../storage/parking_records.jsonl')
    {
        $this->filePath = $filePath;
        $dir = dirname($this->filePath);
        if (!is_dir($dir)) mkdir($dir, 0777, true);
        if (!file_exists($this->filePath)) touch($this->filePath);
    }

    private function readLines(): array
    {
        if (!file_exists($this->filePath)) return [];
        $lines = file($this->filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        return array_map(fn($l) => json_decode($l, true), $lines);
    }

    private function writeAll(array $rows): void
    {
        $content = implode(PHP_EOL, array_map(fn($r) => json_encode($r, JSON_UNESCAPED_UNICODE), $rows)) . PHP_EOL;
        file_put_contents($this->filePath, $content);
    }

    public function create(ParkingRecord $record): int
    {
        $rows = $this->readLines();
        $last = empty($rows) ? 0 : end($rows)['id'];
        $id = $last + 1;
        $record->id = $id;

        $row = [
            'id' => $id,
            'vehicleId' => $record->vehicleId,
            'entryTime' => $record->entryTime->format('Y-m-d H:i:s'),
            'leaveTime' => $record->leaveTime?->format('Y-m-d H:i:s'),
            'price' => $record->price,
        ];

        file_put_contents($this->filePath, json_encode($row, JSON_UNESCAPED_UNICODE) . PHP_EOL, FILE_APPEND);

        return $id;
    }

    public function update(ParkingRecord $record): bool
    {
        $rows = $this->readLines();
        $updated = false;
        $new = [];

        foreach ($rows as $r) {
            if ($r['id'] === $record->id) {
                $r = [
                    'id' => $record->id,
                    'vehicleId' => $record->vehicleId,
                    'entryTime' => $record->entryTime->format('Y-m-d H:i:s'),
                    'leaveTime' => $record->leaveTime?->format('Y-m-d H:i:s'),
                    'price' => $record->price,
                ];
                $updated = true;
            }
            $new[] = $r;
        }

        if (!$updated) return false;
        $this->writeAll($new);
        return true;
    }

    public function findById(int $id): ?ParkingRecord
    {
        foreach ($this->readLines() as $r) {
            if ($r['id'] === $id) {
                return $this->mapRow($r);
            }
        }
        return null;
    }

    public function findAll(): array
    {
        return array_map(fn($r) => $this->mapRow($r), $this->readLines());
    }

    public function findOpenByVehicleId(int $vehicleId): ?ParkingRecord
    {
        $rows = array_filter($this->readLines(), fn($r) => $r['vehicleId'] === $vehicleId && empty($r['leaveTime']));
        if (empty($rows)) return null;
        $last = end($rows);
        return $this->mapRow($last);
    }

    private function mapRow(array $r): ParkingRecord
    {
        $rec = new ParkingRecord((int)$r['vehicleId'], new DateTime($r['entryTime']));
        $rec->id = (int)$r['id'];
        $rec->leaveTime = !empty($r['leaveTime']) ? new DateTime($r['leaveTime']) : null;
        $rec->price = isset($r['price']) ? (float)$r['price'] : 0.0;
        return $rec;
    }
}
