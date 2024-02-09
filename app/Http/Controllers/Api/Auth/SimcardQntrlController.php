<?php

namespace App\Http\Controllers\Api\Auth;



use Sammyjo20\Saloon\Http\Auth\AccessTokenAuthenticator;
use Sammyjo20\Saloon\Traits\Sendsdata;
use App\Models\Qntrl;
use App\Models\radCheck;
use App\Models\radUserGroup;
use App\Models\radReply;
use App\Models\RadiusIP;
use App\Models\Reseller;
use App\Models\TenantConfiguration;
use App\Http\Controllers\Controller;
use App\Models\QntrlCard;
use DateTime;
use Carbon\Carbon;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Http\data;
use App\Models\QntrlOrder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Njoguamos\LaravelZohoOauth\Models\ZohoOauth;
use DB;
use App\Http\Controllers\Api\Auth\SimcardQntrlController;

class SimcardQntrlController extends Controller
{
    public function index()
    {
        $reseller = Reseller::find(1);
        $tenant = tenant();

        $token = ZohoOauth::on('mysql')->latest()->first();
        $accessToken = 'Zoho-oauthtoken ' . $token->access_token;

        $cards = [];
        $response = Http::withHeaders([
            'Authorization' => $accessToken
            ])
            ->get('https://orchestly.zoho.com.au/blueprint/api/buroservaustralia/job?');


        if ($response->successful()) {
            $return_cards = json_decode($response, true);

            if($tenant->id == 'admin'){
                $cards = array_filter($return_cards['job_list'], function ($card) {
                    return isset($card['layout_name']) && $card['layout_name'] === 'SIM Card Activation';
                });
            }else{
                foreach($return_cards['job_list'] as $card){
                        $cards[] = $card;
                }
            }
        }

        return view('api.auth.Simcard_qntrl.index',['cards' => $cards]);
    }


    public function create(array $data = null)
    {
        return view('api.auth.Simcard_qntrl.create');

    }

    public function store()
    {
        $data = request()->all();
        $token = ZohoOauth::on('mysql')->latest()->first();
        $accessToken = 'Zoho-oauthtoken ' . $token->access_token;
        $postData = [
            'title'=>$data['title'],
            'customfield_shorttext84' =>$data['first_name'],
            'customfield_shorttext85' =>$data['last_name'],
            'customfield_shorttext73' =>$data['first_name'],
            'customfield_longtext6' => $data['job_title'],
            'customfield_longtext7' =>$data['email'],
            'customfield_shorttext75'=> $data['date_of_birth'],
            'customfield_shorttext79'=> $data['line_one'],
            'customfield_shorttext78'=> $data['line_two'],
            'customfield_shorttext62'=> $data['line_three'],
            'customfield_shorttext61'=> $data['city'],
            'customfield_shorttext66'=> $data['state'],
            'customfield_shorttext65'=> 'AUS',
            'customfield_shorttext67'=> $data['postal_code'],
            'customfield_shorttext76'=> $data['phone_number'],
            'customfield_shorttext69'=> $data['port_or_new'],
            // 'customfield_shorttext68'=> $data['new_mobile_number'],
            // 'customfield_shorttext71'=> $data['mobile_number_to_port'],
            // 'customfield_shorttext87'=> $data['simcard_number'],
            // 'customfield_shorttext70'=> $data['loosing_carrier'],
            // 'customfield_shorttext72'=> $data['loosing_carrier_account'],
            'customfield_shorttext80'=> $data['site'],
            'layout_id' => '1399000001544403',
        ];

        $response = Http::asForm()->withHeaders([
            'Authorization' => $accessToken
            ])->post('https://orchestly.zoho.com.au/blueprint/api/buroservaustralia/job', $postData);

            if ($response->successful()) {
                $this->handleSuccessfulResponse($response->body());
            } else {
                dd($response->body());
            }

    }


    public function orderCreateCard(array $data)
    {
        $token = ZohoOauth::on('mysql')->latest()->first();
        $accessToken = 'Zoho-oauthtoken ' . $token->access_token;
        $postData = [
            'title'=>$data['title'],
            'customfield_shorttext84' =>$data['first_name'],
            'customfield_shorttext85' =>$data['last_name'],
            'customfield_shorttext73' =>$data['first_name'],
            'customfield_longtext6' => $data['website'],
            'customfield_shorttext74'=> $data['job_title'],
            'customfield_longtext7' =>$data['email'],
            'customfield_shorttext75'=> $data['date_of_birth'],
            'customfield_shorttext79'=> $data['line_one'],
            'customfield_shorttext78'=> $data['line_two'],
            'customfield_shorttext62'=> $data['line_three'],
            'customfield_shorttext61'=> $data['city'],
            'customfield_shorttext66'=> $data['state'],
            'customfield_shorttext65'=> 'AUS',
            'customfield_shorttext86' => $data['gender'],
            'customfield_shorttext67'=> $data['postal_code'],
            'customfield_shorttext76'=> $data['phone_number'],
            'customfield_shorttext69'=> $data['port_or_new'],
            // 'customfield_shorttext68'=> $data['new_mobile_number'],
            // 'customfield_shorttext71'=> $data['mobile_number_to_port'],
            // 'customfield_shorttext87'=> $data['simcard_number'],
            // 'customfield_shorttext70'=> $data['loosing_carrier'],
            // 'customfield_shorttext72'=> $data['loosing_carrier_account'],
            'customfield_shorttext80'=> $data['site'],
            'layout_id' => '1399000001544403',
        ];

        $response = Http::asForm()->withHeaders([
            'Authorization' => $accessToken
            ])->post('https://orchestly.zoho.com.au/blueprint/api/buroservaustralia/job', $postData);

            if ($response->successful()) {
                return $this->handleSuccessfulResponse($response->body());
            } else {
                return $response->body();
            }

    }

    public function show($cardId)
    {

        $token = ZohoOauth::on('mysql')->latest()->first();
        $accessToken = 'Zoho-oauthtoken ' . $token->access_token;

        $cards = [];
        $response = Http::withHeaders([
            'Authorization' => $accessToken
            ])
            ->get('https://orchestly.zoho.com.au/blueprint/api/buroservaustralia/job/'.$cardId);


        if ($response->successful()) {
            $card = json_decode($response, true);
            $card = $card['job_details'];
        }

        return view('api.auth.Simcard_qntrl.show', ['card' => $card]);
    }

    public function edit($cardId)
    {
        $token = ZohoOauth::on('mysql')->latest()->first();
        $accessToken = 'Zoho-oauthtoken ' . $token->access_token;

        $cards = [];
        $response = Http::withHeaders([
            'Authorization' => $accessToken
            ])
            ->get('https://orchestly.zoho.com.au/blueprint/api/buroservaustralia/job/'.$cardId);


        if ($response->successful()) {
            $card = json_decode($response, true);
            $card = $card['job_details'];
        }
        return view('api.auth.Simcard_qntrl.edit', ['card' => $card]);
    }

    public function update(Request $request)
    {
        $token = ZohoOauth::on('mysql')->latest()->first();
        $accessToken = 'Zoho-oauthtoken ' . $token->access_token;
        $jobID = $request->input('id');

        $data=[];
        $headers = [
            'Authorization' => $accessToken,
        ];

        $data['customfield_shorttext84'] = $request->input('customfield_shorttext84');
        $data['customfield_shorttext85'] = $request->input('customfield_shorttext85');
        $data['customfield_shorttext74'] = $request->input('customfield_shorttext74');
        $data['customfield_shorttext79'] = $request->input('customfield_shorttext79');
        $data['customfield_shorttext73'] = $request->input('customfield_shorttext73');

        $queryString = http_build_query($data);

        $url = "https://orchestly.zoho.com.au/blueprint/api/buroservaustralia/job/{$jobID}?{$queryString}";

        $response = Http::withHeaders($headers)->post($url, $data);
        $responseBody = $response->body();

        if($response->successful()){
           dd("ok");   //todo add quntrl card table create part
        }

        return to_route('qntrl.show', $cardId);
    }

    public function activation(Request $request)
    {
        $order_id = $request->query('order_id', null);
        $token = ZohoOauth::on('mysql')->latest()->first();
        $accessToken = 'Zoho-oauthtoken ' . $token->access_token;
        $jobID = '1399000001' . $order_id;

        $data=[];
        $headers = [
            'Authorization' => $accessToken,
        ];

        $data['customfield_shorttext104'] = $order_id;
        $queryString = http_build_query($data);

        $url = "https://orchestly.zoho.com.au/blueprint/api/buroservaustralia/job/{$jobID}?{$queryString}";


        $response = Http::withHeaders($headers)->post($url, $data);
        $responseBody = $response->body();

        if ($response->successful()) {
////////////////////////////////////////////////////////////////////////////////////////////////
            // $cards = [];
            // $response = Http::withHeaders([
            //     'Authorization' => $accessToken
            //     ])
            //     ->get('https://orchestly.zoho.com.au/blueprint/api/buroservaustralia/job/'. $jobID);

            //     if ($response->successful()) {
            //         $card = json_decode($response, true);
            //         $card = $card['job_details'];

            //         dd($card);
            //     }

            return response()->json(['message' => 'API call successful']);
        }

        return response()->json(['error' => 'API call failed', 'message' => $response->body()], $response->status());
    }
    

    public function otpverify(Request $request)
    {

        $order_id = $request->query('order_id', null);
        $code = $request->query('code', null);
        
        $token = ZohoOauth::on('mysql')->latest()->first();
        $accessToken = 'Zoho-oauthtoken ' . $token->access_token;
        $jobID = '1399000001' . $order_id;

        $data=[];
        $headers = [
            'Authorization' => $accessToken,
        ];
      
        $data['customfield_shorttext88'] = $code;
        $data['customfield_shorttext105'] = "Success";
        $queryString = http_build_query($data);
        
        $url = "https://orchestly.zoho.com.au/blueprint/api/buroservaustralia/job/{$jobID}?{$queryString}";
    

        $response = Http::withHeaders($headers)->post($url, $data);
        $responseBody = $response->body();
      
        if ($response->successful()) {
            // $cards = [];
            // $response = Http::withHeaders([
            //     'Authorization' => $accessToken
            //     ])
            //     ->get('https://orchestly.zoho.com.au/blueprint/api/buroservaustralia/job/'. $jobID);

            //     if ($response->successful()) {
            //         $card = json_decode($response, true);
            //         $card = $card['job_details'];

            //         dd($card);
            //     }

            return response()->json(['message' => 'API call successful']);
        }

        return response()->json(['error' => 'API call failed', 'message' => $response->body()], $response->status());


    }



    private function handleSuccessfulResponse($responseData)
    {
        $responseData = json_decode($responseData, true);

        $token = ZohoOauth::on('mysql')->latest()->first();
        $accessToken = 'Zoho-oauthtoken ' . $token->access_token;
        $orderid = substr($responseData['id'], -6);
        $jobID = $responseData['id'];
        $headers = [
            'Authorization' => $accessToken,
        ];

        $data['customfield_shorttext89'] = $orderid;

        $url = "https://orchestly.zoho.com.au/blueprint/api/buroservaustralia/job/{$jobID}";
        $response = Http::asForm()->withHeaders($headers)->post($url, $data);

        $responseBody = $response->body();

        if ($response->successful()) {
            return $this->index();
        } else {
            dd($responseBody);
        }
    }

    private function fetchCardDetails($cardId)
    {
        $token = ZohoOauth::on('mysql')->latest()->first();
        $accessToken = 'Zoho-oauthtoken ' . $token->access_token;

        $cards = [];
        $response = Http::withHeaders([
            'Authorization' => $accessToken
        ])->get('https://orchestly.zoho.com.au/blueprint/api/buroservaustralia/job/' . $cardId);

        if ($response->successful()) {
            return json_decode($response->body(), true)['job_details'];
        }

        return null; // or handle the failure case accordingly
    }



}
