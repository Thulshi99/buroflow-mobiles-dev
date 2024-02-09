<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tenant;
use App\Models\Team;
use App\Models\User;
use App\Models\Reseller;
use DB;
//use Redirect;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

// use App\Model\Website;


class ResellerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // dd("hi");
        return view('reseller.search');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('reseller.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $name = $request->name;
        $email = $request->email;
        $password = $request->password;
        $domain = $request->domain;
        $reseller_id = $request->reseller_id;

        // duplicate data to Admin
        tenancy()->initialize('admin');
        User::create([
            "name" => $name,
            "email" => $email,
            "password" => Hash::make($password),
            "tenant_role" => 'admin'
        ]);

        Reseller::create([
            "reseller_id" => $reseller_id,
            "reseller_name" => $name,
            "reseller_email_address" => $email
        ]);
        tenancy()->end();

        $tenant = Tenant::create(['id' => $request->domain]);
        $tenant->domains()->create(['domain' => $request->domain.'.localhost:8000']);

        User::create([
            "name" => $name,
            "email" => $email,
            "password" => Hash::make($password),
            "tenant_role" => 'admin'

        ]);

        $tenant->run(function () use ($name, $email, $password, $domain, $reseller_id) {
            User::create([
                "name" => $name,
                "email" => $email,
                "password" => Hash::make($password),
                "tenant_role" => 'admin'

            ]);

            Team::create([
                "user_id" => 1,
                "tenant_id" => $domain,
                "name" => "Administration",
                "personal_team" => 0,
                "central_access" => 0,
            ]);

            Reseller::create([
                "reseller_id" => $reseller_id,
                "reseller_name" => $name,
                "reseller_email_address" => $email
            ]);
        });

        return Redirect::to('http://'.$request->domain.'.localhost:8000');
        // return Redirect::back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

    }

    public function search_query()
    {
        Tenant::all()->eachCurrent(function(Tenant $tenant) {
            // the passed tenant has been made current
            // dd($tenant);
            Tenant::current()->is($tenant); // returns true;
        });
    }
}
