<?php

namespace App\Imports;

use App\Models\MobileService;
use App\Models\DataPool;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class MobileServicesImport implements ToCollection
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            dd($row);
            if (!isset($row['Mobile_Nbr']) || !isset($row['Datapool_Description'])) {
                continue;
            }

            // Find datapool_id
            $datapool = DataPool::where('description', $row['Datapool_Description'])->first();

            if ($datapool) {
                // Find MobileService
                $mobileService = MobileService::where('mobile_no', $row['mobile_no'])->first();

                if ($mobileService) {
                    $mobileService->datapool_id = $datapool->id;
                    $mobileService->save();
                } else {
                    continue;
                }
            } else {
                continue;
            }
        }
    }
}
