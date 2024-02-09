<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\SimCard;
use Illuminate\Support\Str;
use Auth;
use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Models\CustomerContactInfo;
use App\Models\PortingDetails;
use CodeDredd\Soap\Facades\Soap;
use Artisaninweb\SoapWrapper\SoapWrapper;
use App\Http\Requests\PortBindingServiceRequest;
use App\Jobs\OrderProcessInBackground;
use App\Http\Controllers\Api\Auth\SimcardQntrlController;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\OrderCreateRequest;
use App\Http\Requests\OctaneReserveMobileNumberRequest;
use App\Http\Requests\OctaneSelectReserveNumberRequest;
use App\Http\Requests\OctaneSimCardStatusRequest;
use GuzzleHttp\Client;
use SimpleXMLElement;
class OrderController extends Controller
{

    /**
     * @var SoapWrapper
    */
    protected $soapWrapper;

    /**
     * SoapController constructor.
     *
     * @param SoapWrapper $soapWrapper
     */
    // public function __construct(SoapWrapper $soapWrapper)
    // {
    //     $this->soapWrapper = $soapWrapper;
    // }


    public function index(Request $request)
    {

        $order_status = $request->order_status;
        return view('orders.index')
        ->with('order_status', $order_status);
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
    public function store(array $data)
    {

        /*
        //
        //  QTRL REQUEST
        //
        */
        if($data['existing_or_new_customer'] === "new"){
            $cardData = [
                'title' => 'New Mobile Order - '.$data['first_name']." ".$data['mid_name'],
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'phone_number' => $data['current_phone_number'],
                'job_title' => $data['job_title'],
                'website' => $data['website'],
                'email' => $data['email'],
                'date_of_birth' => $data['date_of_birth'],
                'line_one' => $data['line_one'],
                'line_two' => $data['line_two'],
                'line_three' => $data['line_three'],
                'city' => $data['city'],
                'gender' => $data['gender'],
                'state' => $data['state'],
                'postal_code' => $data['postal_code'],
                'port_or_new' => $data['port_or_new_number'],
                // 'new_mobile_number' => $data['current_phone_number'],
                // 'mobile_number_to_port' => $data['current_phone_number'],
                // 'simcard_number' => $data['current_phone_number'],
                // 'loosing_carrier' => $data['current_phone_number'],
                // 'loosing_carrier_account' => $data['current_phone_number'],
                'site' => $data['cost_centre'],
            ];
        }else{
            $customer = Customer::find($data['customer_id']);
            $customer_address = CustomerAddress::where('customer_id',$customer->id)->first();
            $customer_contact_info = CustomerContactInfo::where('customer_id',$customer->id)->first();
            $cardData = [
                'title' => 'Customer Mobile Order - '.$customer->first_name." ".$customer->last_name,
                'first_name' => $customer->first_name,
                'last_name' => $customer->last_name,
                'phone_number' => $customer->current_phone_number,
                'job_title' => $customer->job_title,
                'email' =>  $customer->email,
                'date_of_birth' => $customer_contact_info->date_of_birth,
                'line_one' => $customer_address->line_one,
                'line_two' => $customer_address->line_two,
                'line_three' => $$customer_address->line_three,
                'city' => $customer_address->city ,
                'state' => $customer_address->state ,
                'gender' => $customer_contact_info->gender ,
                'website' => $customer_contact_info->website ,
                'postal_code' => $customer_address->postal_code,
                // 'port_or_new' => $data['port_or_new_number'],
                // 'new_mobile_number' => $data['current_phone_number'],
                // 'mobile_number_to_port' => $data['current_phone_number'],
                // 'simcard_number' => $data['current_phone_number'],
                // 'loosing_carrier' => $data['current_phone_number'],
                // 'loosing_carrier_account' => $data['current_phone_number'],
                'site' => $data['cost_centre'],
            ];
        }
        //create qntrl card
        $card = new SimcardQntrlController();

        //create card
        // $cardResponse = $card->orderCreateCard($cardData);


        /*
        //
        //  OCTANE REQUEST
        //
        */

        $soapWrapper = app(SoapWrapper::class);

        //Ocatane check simcard stats
        $latest_simcard = SimCard::with('reseller')->latest()->first();
        $data = [
            'username' => env('OCTANE_USERNAME'),
            'password' => env('OCTANE_PASSWORD'),
            'simNo' => $latest_simcard->sim_card_code,
            // 'simNo' => '491703421',
        ];
        $serviceRequest = new OctaneSimCardStatusRequest($data);

        $endpoint = env('OCTANE_BASE_URL','https://benzine.telcoinabox.com/tiab')."/UtbPooledResource?wsdl";

        $soapWrapper->add('UtbPooledResources', function ($service) use ($endpoint){
            $service
                ->wsdl($endpoint) // The WSDL endpoint
                ->trace(true);  // Optional: (parameter: true/false)
        });

        $simcard_status_response = $soapWrapper->call('UtbPooledResources.queryResources', [
            new \SoapVar($serviceRequest->getXmlBody(), XSD_ANYXML)
        ]);
        $simcard_status_return = $simcard_status_response->return;

        //reserve mobible number when simcard status success
        if($simcard_status_return->success && $simcard_status_return->errorCode==0){
            $data = [
                'username' => env('OCTANE_USERNAME'),
                'password' => env('OCTANE_PASSWORD'),
                // 'simNo' => $latest_simcard->sim_card_code,
                // 'simNo' => '491703421',
            ];
            $serviceRequest = new OctaneReserveMobileNumberRequest($data);

            $endpoint = env('OCTANE_BASE_URL','https://benzine.telcoinabox.com/tiab')."/UtbPooledResource?wsdl";

            // $this->soapWrapper->add('UtbPooledResources', function ($service) use ($endpoint){
            //     $service
            //         ->wsdl($endpoint) // The WSDL endpoint
            //         ->trace(true);  // Optional: (parameter: true/false)
            // });

            $reserve_mobile_response = $soapWrapper->call('UtbPooledResources.reserveResources', [
                new \SoapVar($serviceRequest->getXmlBody(), XSD_ANYXML)
            ]);
            $reserve_mobile_return = $reserve_mobile_response->return;
            //select resource request when reserve resource success
            if($reserve_mobile_return->success && $reserve_mobile_return->errorCode==0){
                $data = [
                    'username' => env('OCTANE_USERNAME'),
                    'password' => env('OCTANE_PASSWORD'),
                    'mobileNo' => $reserve_mobile_return->reservedPooledResources->resourceValue,
                    // 'mobileNo' => '491703421',
                ];
                $serviceRequest = new OctaneSelectReserveNumberRequest($data);

                $endpoint = env('OCTANE_BASE_URL','https://benzine.telcoinabox.com/tiab')."/UtbPooledResource?wsdl";

                $select_mobile_response = $soapWrapper->call('UtbPooledResources.selectResource', [
                    new \SoapVar($serviceRequest->getXmlBody(), XSD_ANYXML)
                ]);
                $select_mobile_return = $select_mobile_response->return;


                //order create request when success select mobile request
                if($select_mobile_return->success && $select_mobile_return->errorCode==0){
                    //octane request
                    $latest_simcard = SimCard::with('reseller')->latest()->first();
                    $data = [
                        'username' => env('OCTANE_USERNAME'),
                        'password' => env('OCTANE_PASSWORD'),
                        'custNo' => env('OCTANE_DEFAULT_CUSTNO'),
                        'simNo' => $latest_simcard->sim_card_code,
                        // 'simNo' => '491703421',
                        'msn'=>$reserve_mobile_return->reservedPooledResources->resourceValue,
                    ];
                    $serviceRequest = new OrderCreateRequest($data);

                    $endpoint = env('OCTANE_BASE_URL','https://benzine.telcoinabox.com/tiab')."/UtbOrder?wsdl";

                    $soapWrapper->add('UtbOrder', function ($service) use ($endpoint){
                        $service
                            ->wsdl($endpoint) // The WSDL endpoint
                            ->trace(true);  // Optional: (parameter: true/false)
                    });

                    $order_create_response = $soapWrapper->call('UtbOrder.orderCreate', [
                        new \SoapVar($serviceRequest->getXmlBody(), XSD_ANYXML)
                    ]);

                    $order_create_response_return = $order_create_response->return;
                    // dd($order_create_response_return);

                    //Send selcomm request and save customer data in DB
                    if($order_create_response_return->errorCode==0){
                        /*
                        //
                        //  SELCOMM REQUEST
                        //
                        */

                        if(isset($data['existing_or_new_customer'])){
                            //need to add enums to types
                            if($data['existing_or_new_customer'] === "new"){

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
                                        "DateOfBirth": "'.$data["date_of_birth"].'",
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



                            }else{
                                $customer_id = $data["customer_id"];

                            }
                        }

                        /*
                        //
                        //  SAVE ORDER IN DB
                        //
                        */

                        if(isset($data['port_or_new_number'])){
                            //need to implement enums
                            if($data['port_or_new_number'] === "new"){
                                $mobile_number = 'N/A';
                            }else{
                                $mobile_number = $data['mobile_number'];
                            }
                        }

                        $order = new Order();
                        $order->company_id = 1; //set correct company ID
                        $order->order_id = rand(10000, 99999);
                        $order->octane_order_id = $order_create_response_return->orderId;
                        $order->order_status = "new-order";
                        $order->mobile_number = $mobile_number;
                        $order->vendor_id = $data["vendor_id"];
                        $order->site = $data["cost_centre"];
                        $order->customer_id = $customer_id;
                        $order->retail_package_id = $data["retail_package_id"];
                        $order->reseller_id = $data["reseller_id"];
                        $order->save();




                        // if(isset($data['port_or_new_number'])){
                        //     //need to implement enums
                        //     if($data['port_or_new_number'] === "port"){
                        //         // $order_id = Order::latest()->first()->id;
                        //         // $porting_details = new PortingDetails();
                        //         // $porting_details->
                        //         // $
                        //     }else{
                        //         // $simcard = SimCard::find($data['mobile_number']);
                        //         // $simcard->status = "allocated";
                        //         // $simcard->save();
                        //     }
                        // }




                        return to_route('orders.index');


                    } // octane order create request success end
                    session()->flash('error', 'Your custom error message.');
                    return to_route('mobileplans.assign');
                }

            }
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
        return view('orders.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $order = Order::find($id);
        return view('orders.edit')
            ->with('id',$id);
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
        $order = Order::find($id);
        $order->update($data);
        return to_route('orders.index');
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
