<?php

namespace App\Http\Controllers;

use App\Models\QntrlOrder;
use App\Http\Requests\StoreQntrlOrderRequest;
use App\Http\Requests\UpdateQntrlOrderRequest;

class QntrlOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \App\Http\Requests\StoreQntrlOrderRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreQntrlOrderRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\QntrlOrder  $qntrlOrder
     * @return \Illuminate\Http\Response
     */
    public function show(QntrlOrder $qntrlOrder)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\QntrlOrder  $qntrlOrder
     * @return \Illuminate\Http\Response
     */
    public function edit(QntrlOrder $qntrlOrder)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateQntrlOrderRequest  $request
     * @param  \App\Models\QntrlOrder  $qntrlOrder
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateQntrlOrderRequest $request, QntrlOrder $qntrlOrder)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\QntrlOrder  $qntrlOrder
     * @return \Illuminate\Http\Response
     */
    public function destroy(QntrlOrder $qntrlOrder)
    {
        //
    }
}
