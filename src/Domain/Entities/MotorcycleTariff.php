<?php 

require_once "Tariff.php";

class MotorcycleTariff extends Tariff
{
    public function __construct()
    {
        parent::__construct(3);
    }
}