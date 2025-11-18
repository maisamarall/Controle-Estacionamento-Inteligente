<?php

declare(strict_types=1);

namespace App\Domain\Entities;

use DateTime;

class Vehicle
{
    public $id;
    public $plate;
    public $type;
    public DateTime $entryTime;
    public ?DateTime $leaveTime;

    public function __construct(string $plate, string $type)
    {
        $this->plate = $plate;
        $this->type = $type;
        $this->entryTime = new DateTime();
        $this->leaveTime = null;
    }

    public function registerLeave(): void
    {
        $this->leaveTime = new DateTime();
    }

    public function getId(): int {return $this->id;}
    public function setId(int $id): void {$this->id = $id;}
    public function getPlate(): string { return $this->plate; }
    public function getType(): string { return $this->type; }
    public function getEntryTime(): \DateTime { return $this->entryTime; }
    public function getLeaveTime(): ?\DateTime { return $this->leaveTime; }
}