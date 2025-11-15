<?php 

require_once "Tariff.php";

class TruckTariff extends Tariff
{
    public function __construct()
    {
        parent::__construct(10);
    }
}