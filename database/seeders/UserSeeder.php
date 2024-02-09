<?php

namespace Database\Seeders;

use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user_details = [
            ['name' => "Retailer",'email' => "retail@mail.com",'team' => 'Retailer Team'],
            ['name' => "Reseller",'email' => "resell@mail.com",'team' => 'Reseller Team'],
        ];

        foreach($user_details as $details) {

            $user = User::factory()->create([
                'name' => $details['name'],
                'email' => $details['email'],
                'password' => bcrypt('supersecret'),
            ]);
            
            $team = Team::factory()->create([
                'name' => $details['team'],
                'user_id' => $user->id,
                'tenant_id' => ucfirst($details['name']),
                'personal_team' => false,
            ]);
            $user->teams()->attach($team);
            $user->switchTeam($team);
        }
    }
}
