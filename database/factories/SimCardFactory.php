<?php

namespace Database\Factories;

use App\Models\Carrier;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SimCard>
 */
class SimCardFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $tenants = \App\Models\Tenant::all()->pluck('id')->toArray();
        $imei = $this->faker->numerify('4############');

        return [
            'tenant_id' => Arr::random($tenants),
            'imei' => $imei,
            'imsi' => $this->faker->numerify('505############'),
            'iccid' => "896101${imei}",
            'puk' => $this->faker->numerify('########'),
            'status' => $this->faker->biasedNumberBetween(1, 7),
            'carrier_id' => Carrier::all()->random()->id,
            'tracking_id' => $this->faker->numerify('########'),
            'service_id' => $this->faker->numerify('61#######'),
            'exists_in_iboss' => $this->faker->boolean(30),
            'updated_at' => $this->faker->boolean() ? $this->faker->dateTimeBetween($startDate = '-6 month', $endDate = 'now') : null,
            'created_at' => $this->faker->dateTimeBetween($startDate = '-6 month', $endDate = 'now'),
        ];
    }
}
