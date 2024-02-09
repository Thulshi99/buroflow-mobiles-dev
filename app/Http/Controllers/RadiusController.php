<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\radReply;
use App\Models\radCheck;
use App\Models\radUserGroup;
use App\Models\RadiusIP;
use App\Models\IPPoolIMS;
use App\Models\IMSRealms;

class RadiusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $radiusUserGroup = radUserGroup::all()
        ->SortByDesc('updated')
        ->first();

        $radiusCheck = radCheck::all()
        ->SortByDesc('updated')
        ->first();

        $radiusReply = radReply::all()
        ->SortByDesc('updated')
        ->first();

        $imsIPpool = IPPoolIMS::all()
        ->SortByDesc('ip_id')
        ->first();

        $realms = IMSRealms::pluck('realm_name', 'realm_id')
        ->toArray();

        $radiusIP = RadiusIP::where('buroflow_reference', '')->first();


    return [$imsIPpool, $radiusUserGroup, $radiusCheck, $radiusReply, $radiusIP];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
    }
}
