<?php

interface TariffInterface {
    public function calculatePrice(DateTime $entry, Datetime $leave): float;
}