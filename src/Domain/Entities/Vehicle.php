<?php
declare(strict_types=1);

namespace App\Domain\Entities;

use DateTime;

class Vehicle
{
    public int $id;
    public string $plate;
    public string $type;
    public DateTime $entryTime;
    public ?DateTime $leaveTime;

    public function __construct(string $plate, string $type)
    {
        $this->plate = $plate;
        $this->type = $type;
        $this->entryTime = new DateTime();
        $this->leaveTime = null;
    }
}