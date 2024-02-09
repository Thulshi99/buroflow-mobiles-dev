<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CarrierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $carriers = [
            'Telstra', 'Optus', 'iiNet', 'Dodo', 'SuperLoop', 
            'Telstra Enterprise', 'Vodafone', 'AAPT', 'Iseek',
            'Cirrus'
        ];

        foreach ($carriers as $carrier) {
            \App\Models\Carrier::factory()->create(['name' => $carrier]);
        }
    }
}
