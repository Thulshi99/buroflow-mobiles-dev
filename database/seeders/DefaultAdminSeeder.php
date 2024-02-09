<?php

namespace Database\Seeders;

use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DefaultAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::factory()->create([
            'name' => "Admin",
            'email' => "admin@mail.com",
            'password' => bcrypt('supersecret'),
        ]);

        $adminTeam = Team::factory()->create([
            'name' => 'Administration',
            'user_id' => $admin->id,
            'tenant_id' => "Admin",
            'personal_team' => false,
            'central_access' => true,
        ]);

        $admin->teams()->attach($adminTeam);
        $admin->switchTeam($adminTeam);

        
    }
}
