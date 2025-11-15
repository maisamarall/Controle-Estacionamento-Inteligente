<?php 

require_once "Tariff.php";

class CarTariff extends Tariff
{
    public function __construct()
    {
        parent::__construct(5);
    }
}