<?php

namespace Database\Seeders;

use App\Models\Tenant;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Laravel\Passport\ClientRepository;


class TenantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $client = new ClientRepository();

        $client->createPasswordGrantClient(1, 'Default password grant client', 'http://your.redirect.path');
        $client->createPersonalAccessClient(2, 'Default personal access client', 'http://your.redirect.path');

        $domain = config('app.url');
        $tenants = [
            ['id' => "Admin",'domain' => "admin.{$domain}"],
            ['id' => "Retailer",'domain' => "retailer.{$domain}"],
            ['id' => "Reseller",'domain' => "reseller.{$domain}"],
        ];

        foreach($tenants as $tenant) {

            $new_tenant = Tenant::create([ 'id' => $tenant['id'] ]);
            $new_tenant->domains()->create([ 'domain' => $tenant['domain'] ]);

        }
    }
}
