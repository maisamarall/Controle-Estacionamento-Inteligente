<?php
declare(strict_types=1);

namespace App\Application\Services;

use App\Domain\Interfaces\ParkingRecordRepositoryInterface;
use App\Domain\Interfaces\VehicleRepositoryInterface;

final class ReportService
{
    public function __construct(
        private ParkingRecordRepositoryInterface $recordRepo,
        private VehicleRepositoryInterface $vehicleRepo
    ){}

    public function generateReportByType(): array
    {
        $records = $this->recordRepo->findAll(); 
        $acc = [];
        $totalRevenue = 0.0;

        foreach ($records as $r) {
            if (!$r->hasLeft() || $r->price === null) continue; 
            
            $vehicle = $this->vehicleRepo->getById($r->vehicleId);
            if (!$vehicle) continue;
            
            $type = strtolower($vehicle->type);
            
            if (!isset($acc[$type])) $acc[$type] = ['count' => 0, 'revenue' => 0.0];
            
            $acc[$type]['count'] += 1;
            $acc[$type]['revenue'] += $r->price;
            
            $totalRevenue += $r->price;
        }

        $acc['total'] = ['revenue' => $totalRevenue]; 
        
        return $acc;
    }
}