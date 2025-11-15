<?php 

require_once "TariffInterface.php";

class Tariff implements TariffInterface
{
    private float $hourPrice;

    public function __construct(float $hourPrice)
    {
        $this->hourPrice = $hourPrice;
    }

    public function calculatePrice(DateTime $entry, DateTime $leave): float
    {
        $seconds = $leave->getTimestamp() - $entry->getTimestamp();

        if ($seconds <= 0)
        {
            throw new \InvalidArgumentException('O horário de saída deve ser após o horário de entrada.');
        }

        $hours = $seconds / 3600.0;

        $chargedHours = max(1, ceil($hours));

        return $chargedHours * $this->hourPrice;
    }
}