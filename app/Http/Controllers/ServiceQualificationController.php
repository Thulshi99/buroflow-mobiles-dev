<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreServiceQualificationRequest;
use App\Http\Requests\UpdateServiceQualificationRequest;
use App\Models\ServiceQualification;
use Barryvdh\DomPDF\Facade\Pdf;
class ServiceQualificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    //     $data = [
    //         'abc'=> 'dddd',
    //     ];
    //     $pdf = Pdf::loadView('supporttickets.invoice', $data);
    // return $pdf->download('invoice.pdf');
        return view('sq.list');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('sq.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreServiceQualificationRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreServiceQualificationRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ServiceQualification  $serviceQualification
     * @return \Illuminate\Http\Response
     */
    public function show(ServiceQualification $serviceQualification)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ServiceQualification  $serviceQualification
     * @return \Illuminate\Http\Response
     */
    public function edit(ServiceQualification $serviceQualification)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateServiceQualificationRequest  $request
     * @param  \App\Models\ServiceQualification  $serviceQualification
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateServiceQualificationRequest $request, ServiceQualification $serviceQualification)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ServiceQualification  $serviceQualification
     * @return \Illuminate\Http\Response
     */
    public function destroy(ServiceQualification $serviceQualification)
    {
        //
    }
}
