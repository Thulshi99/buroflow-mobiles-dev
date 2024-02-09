<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Models\CustomerContactInfo;
use Illuminate\Support\Str;
use Auth;
use Illuminate\Support\Facades\Http;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Requests\StoreCustomerRequest;
use GuzzleHttp\Client;


class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('customers.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('customers.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(array $data)
    {

        $headers = [
          'Content-Type' => 'application/json',
          'Authorization' => 'Bearer '.env('SELCOMM_BU_TOKEN')
        ];
        $client = new Client(
            [
                'headers' => $headers
            ]
        );


        try {
            $body = '{
                "BusinessUnitCode": "DE",
                "Type": "corporation",
                "SubTypeId": "",
                "StatusId": "I",
                "Name": "'.$data['first_name'].'",
                "FirstName": "'.$data['first_name'].'",
                "Title": "'.$data["job_title"].'",
                "DateOfBirth": "1990-01-01T00:00:00",
                "Gender": "'.$data["gender"].'",
                "TradingName": "'.$data["first_name"].'",
                "BusinessNumber": "69131636836",
                "Email": "tnnmuhandiram@gmail.com",
                "HomePhone": "'.$data["current_phone_number"].'",
                "WorkPhone": "'.$data["current_phone_number"].'",
                "MobilePhone": "'.$data["current_phone_number"].'",
                "BillingAddress": {
                  "Address1": "'.$data["line_one"].'",
                  "Address2": "'.$data["line_two"].'",
                  "Suburb": "'.$data["line_three"].'",
                  "City": "'.$data["city"].'",
                  "State": "'.$data["state"].'",
                  "Postcode": "'.$data["postal_code"].'",
                  "CountryCode": "AU"
                },
                "StreetAddress": {
                    "Address1": "'.$data["line_one"].'",
                    "Address2": "'.$data["line_two"].'",
                    "Suburb": "'.$data["line_three"].'",
                    "City": "'.$data["city"].'",
                    "State": "'.$data["state"].'",
                    "Postcode": "'.$data["postal_code"].'",
                    "CountryCode": "AU"
                },
                "PaperBill": true,
                "EmailBill": true,
                "BillingCycleCode": "TE",
                "DealerId": null,
                "TaxId": 1,
                "TimeZoneId": null,
                "CreditLimit": 0,
                "ParentId": "",
                "ExternalPayId": "600000999999"
              }';


              $r = $client->request('POST', 'https://ua-api.selcomm.com/Accounts/Basic/Person?api-version=1.0', [
                  'body' => $body
              ]);
              $response = $r->getBody()->getContents();
                $array = json_decode($response, true); // The second parameter 'true' converts the JSON object to an associative array

                  $customer = new Customer();
                  $customer->company_id = 1;
                  $customer->customer_code = $array['Id'];
                  $customer->primary_contact_name = $data["primary_contact_name"];
                  $customer->job_title = $data["job_title"];
                  $customer->email = $data["email"];
                  $customer->current_phone_number = $data["current_phone_number"];
                  $customer->allow_override_rate = $data["allow_override_rate"];
                  $customer->payments_allowed = $data["payments_allowed"];
                  $customer->auto_apply_payments = $data["auto_apply_payments"];
                  $customer->print_statements = $data["print_statements"];
                  $customer->send_statement_by_email = $data["send_statement_by_email"];
                  $customer->shared_credit_policy = $data["shared_credit_policy"];
                  $customer->consolidate_statements = $data["consolidate_statements"];
                  $customer->fin_change_apply = $data["fin_change_apply"];
                  $customer->small_balance_allow = $data["small_balance_allow"];
                  $customer->reseller_id = Auth::user()->id;
                  $customer->save();



                  //get last customer address id
                  $customer_id = Customer::latest()->first()->id;


                  //create customer address record
                  $customer_address = new CustomerAddress();
                  $customer_address->customer_id = $customer_id;
                  $customer_address->company_id = 1;
                  $customer_address->line_one = $data["line_one"];
                  $customer_address->line_two = $data["line_two"];
                  $customer_address->line_three = $data["line_three"];
                  $customer_address->city = $data["city"];
                  $customer_address->state = $data["state"];
                  $customer_address->country = $data["country"];
                  $customer_address->postal_code = $data["postal_code"];
                  $customer_address->is_billing  = $data["is_billing"];
                  $customer_address->save();

                  $customer_contact_info = new CustomerContactInfo();
                  $customer_contact_info->customer_id = $customer_id;
                  $customer_contact_info->display_name = $data["first_name"]." ".$data['last_name'];
                  $customer_contact_info->first_name = $data["first_name"];
                  $customer_contact_info->last_name = $data["last_name"];
                  $customer_contact_info->mid_name = $data["mid_name"];
                  $customer_contact_info->website = $data["website"];
                  $customer_contact_info->phone_one = $data["current_phone_number"];
                  $customer_contact_info->gender = $data["gender"];
                  $customer_contact_info->save();




                  return to_route('customers.index');

        }catch (\Exception $e) {

            $error_message = 'API call failed';
            if ($e->hasResponse()) {
                $response = json_decode($e->getResponse()->getBody()->getContents(), true);
                $error_message = $response['message'] ?? $error_message;

            }

            return view('customers.create')->with('error', $error_message);

        }


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $customer = Customer::find($id);
        return view('customers.show')
            ->with('customer_id',$id)
            ->with('company_id',$customer->company_id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $customer = Customer::find($id);
        return view('customers.edit')
            ->with('customer_id',$id)
            ->with('company_id',$customer->company_id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(array $data,$id)
    {
        $customer = Customer::find($id);
        $customer->update($data);

        $customer_address = CustomerAddress::where('customer_id',$id)->first();
        $customer_address->company_id = $data["company_id"];
        $customer_address->line_one = $data["line_one"];
        $customer_address->line_two = $data["line_two"];
        $customer_address->line_three = $data["line_three"];
        $customer_address->city = $data["city"];
        $customer_address->state = $data["state"];
        $customer_address->country = $data["country"];
        $customer_address->postal_code = $data["postal_code"];
        $customer_address->is_billing  = $data["is_billing"];
        $customer_address->update();

        $customer_contact_info = CustomerContactInfo::where('customer_id',$id)->first();
        $customer_contact_info->display_name = $data["first_name"]." ".$data['last_name'];
        $customer_contact_info->first_name = $data["first_name"];
        $customer_contact_info->last_name = $data["last_name"];
        $customer_contact_info->mid_name = $data["mid_name"];
        $customer_contact_info->website = $data["website"];
        $customer_contact_info->date_of_birth = $data["date_of_birth"];
        $customer_contact_info->phone_one = $data["current_phone_number"];
        $customer_contact_info->gender = $data["gender"];
        $customer_contact_info->save();

        return to_route('customers.index');
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
