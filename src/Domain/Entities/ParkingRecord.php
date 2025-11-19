<?php
declare(strict_types=1);

namespace App\Domain\Entities;

use DateTime;

final class ParkingRecord
{
    public int $id;
    public int $vehicleId;
    public DateTime $entryTime;
    public ?DateTime $leaveTime;
    public float $price = 0.0;

    public function __construct(int $vehicleId, ?DateTime $entryTime = null)
    {
        $this->vehicleId = $vehicleId;
        $this->entryTime = $entryTime ?? new DateTime();
        $this->leaveTime = null;
    }

    public function markLeave(DateTime $leaveTime): void
    {
        $this->leaveTime = $leaveTime;
    }

    public function hasLeft(): bool
    {
        return $this->leaveTime !== null;
    }

    public function getTotalHours(): int
    {
        $end = $this->leaveTime ?? new DateTime();
        $diff = $end->getTimestamp() - $this->entryTime->getTimestamp();
        $hours = (int) ceil($diff / 3600);
        return max(1, $hours);
    }

    public function setPrice(float $price): void
    {
        $this->price = $price;
    }
}