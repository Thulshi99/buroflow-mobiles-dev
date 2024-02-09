<?php

namespace App\Imports;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use App\Models\SimCard;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use App\Models\User;
use Illuminate\Support\Str;
class SimcardImport implements ToModel,WithHeadingRow, WithBatchInserts
{
    use Importable;

    private $batch_number;
    private $carrier_id;
    private $reseller_id;

    public function __construct($batch_number,$reseller_id,$carrier_id)
    {
        $this->batch_number = $batch_number;
        $this->carrier_id = $carrier_id;
        $this->reseller_id = $reseller_id;
    }

    public function model(array $row)
    {
        return new SimCard([
            'puk_code' => rand(100000, 999999),
            'sim_card_code' => $row['sim_card_code'],
            'batch_number' => $this->batch_number,
            'mobile_number' => 'NA',
            'status' => 'available',
            'shipvia_id' => $this->carrier_id,
            'company_id' => 1,
            'reseller_id' => $this->reseller_id,
        ]);
    }

    public function batchSize(): int
    {
        return 1000;
    }
}
