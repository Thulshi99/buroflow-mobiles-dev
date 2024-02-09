<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\SupportTicket;
use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Models\CustomerContactInfo;
use Illuminate\Support\Str;
use Auth;

class SupportTicketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('supporttickets.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('supporttickets.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(array $data)
    {


        // $customer = Customer::find( $data["customer_id"])->with('customercontactinfos');

        $support_ticket = new SupportTicket();
        $support_ticket->company_id = 1; //set correct company ID
        $support_ticket->ticket_code = rand(10000, 99999);
        $support_ticket->status = "opened";
        $support_ticket->mobile_service_order_code = $data["mobile_service_order_code"];
        $support_ticket->description = $data["description"];
        $support_ticket->fault_category = $data["fault_category"];
        $support_ticket->customer_id = $data["customer_id"];
        $support_ticket->email = $data["email"];
        $support_ticket->reseller_id = Auth::user()->id;
        $support_ticket->save();

        return to_route('supporttickets.index');

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
