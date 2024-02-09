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

use function PHPUnit\Framework\returnSelf;

class QntrlController extends Controller
{
    protected $check_attribute = "Cleartext-Password";
    protected $reply_attribute = "Framed-IP-Address";
    protected $check_op = ":=";
    protected $reply_op = "=";
    protected $group_groupname = "NBN-UNLIMITED";
    protected $group_priority = 0;
    protected $qntrlData;

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
            ->get('https://orchestly.zoho.com/blueprint/api/buroserv/job?sort_by=modified_date&sort_order=descending');

        if ($response->successful()) {

            $return_cards = json_decode($response, true);

            if($tenant->id == 'admin'){
                $cards = $return_cards['job_list'];
            }else{
                foreach($return_cards['job_list'] as $card){
                    if($card['fields']['customfield_shorttext46'] == $reseller->reseller_id){
                        $cards[] = $card;
                    }
                }
            }
        }

        return view('api.auth.qntrl.index', ['cards' => $cards]);
    }

    public function create(array $data = null)
    {
    
        return to_route('qntrl.index');
    }

    public function store(array $data)    
    {
        $postData = [
            'title'                   => $data['customfield_shorttext22'].' - '.$data['customfield_shorttext14'], // Buroflow Reference + LOC ID
            'layout_id'               => $data['layout_id'], // Qntrl Layout ID
            'duedate'                 => $data['duedate'], // Qntrl Default field - Preferred Transfer Date

            'customfield_date5'       => $data['customfield_date5'], // Customer Authority Date

            'customfield_dropdown4'   => $data['customfield_dropdown4'], // Order Status
            'customfield_dropdown5'   => $data['customfield_dropdown5'], // Access Type
            'customfield_dropdown6'   => $data['customfield_dropdown6'], // NBN Speed
            'customfield_dropdown7'   => $data['customfield_dropdown7'], // Contract term
            'customfield_dropdown9'   => $data['customfield_dropdown9'], // Access Method - Type of NBN service
            
            'customfield_shorttext11' => $data['customfield_shorttext11'], // Post Code
            'customfield_shorttext13' => $data['customfield_shorttext13'], // State
            'customfield_shorttext14' => $data['customfield_shorttext14'], // Location ID
            'customfield_shorttext17' => $data['serviceProviderName'], // Current ISP

            'customfield_shorttext20' => $data['customfield_shorttext20'], // Unit Number
            'customfield_shorttext22' => $data['customfield_shorttext22'], // Buroflow Reference
            'customfield_shorttext23' => $data['customfield_shorttext23'], // Street Number
            'customfield_shorttext24' => $data['reseller'], // Reseller Name
            'customfield_shorttext25' => $data['customfield_shorttext25'], // Reseller Email

            'customfield_shorttext37' => $data['ntdId'], // NTD ID

            'customfield_shorttext42' => $data['cpiId'], // Copper pair ID (copper services)
            'customfield_shorttext46' => $data['resellerId'], // Reseller ID

            'customfield_shorttext7'  => $data['customfield_shorttext7'], // Reseller Mobile Contact
            'customfield_shorttext8'  => $data['customfield_shorttext8'], // Suburb
            'customfield_shorttext9'  => $data['customfield_shorttext9'], // Street Name

            //New Reseller Customer Details
            'customfield_shorttext16' => $data['retail_account'], // Retail Account Number
            'customfield_shorttext18' => $data['customer_reference'], // Customer Reference
            'customfield_shorttext21' => $data['customer_name'], // Customer Name
            'customfield_shorttext34' => $data['site_name'], // Site Name

        ];

        $radiusData = ($data['show_radius'] != false) ? [
            'customfield_shorttext47' => $data['rad_user'].$data['realm'], // Radius Username
            'customfield_shorttext49' => $data['rad_pass'], // Radius Password
            'customfield_shorttext28' => $data['rad_ip'], // Radius IP 
            'customfield_shorttext27' => $data['realmId'], // IMS Realm ID
            'customfield_dropdown3'   => $data['customfield_dropdown3'] // Set Internal or External Order Field
        ] : [ 
            'customfield_shorttext47' => $data['ims_user'], // Radius Username
            'customfield_shorttext49' => null, // Radius Password
            'customfield_shorttext28' => null, // Radius IP 
            'customfield_shorttext27' => null, // IMS Realm ID
            'customfield_dropdown3'   => '31914000000046145' // Set Internal or External Order Field - 31914000000046146 = external
	];

	$port_id = ($data['portId'] == null) ? [
            'customfield_shorttext39' => '1',
        ] : [
            'customfield_shorttext39' => $data['portId'],
	];

        $postData['customfield_shorttext47'] = $radiusData['customfield_shorttext47'];
        $postData['customfield_shorttext49'] = $radiusData['customfield_shorttext49'];
        $postData['customfield_shorttext28'] = $radiusData['customfield_shorttext28'];
        $postData['customfield_shorttext27'] = $radiusData['customfield_shorttext27'];
	    $postData['customfield_dropdown3']   = $radiusData['customfield_dropdown3'];
	    $postData['customfield_shorttext39'] = $port_id['customfield_shorttext39'];

        // Update RadiusIP database table.
        if ($data['show_radius'] != false) {
            $radiusIP = RadiusIP::on('mysql')->find($data['radiusIP_id']);
            $user_id  = Auth::id();
            
            $radiusIP->buroflow_reference = $data['customfield_shorttext22'];
            $radiusIP->user_id = $user_id;
            // Save to the database table.
            $radiusIP->save();
        }

        // Create Qntrl Card for NBN Transfer
        $token = ZohoOauth::on('mysql')->latest()->first();

        $accessToken = 'Zoho-oauthtoken ' . $token->access_token;

        $response = Http::asForm()->withHeaders([
            'Authorization' => $accessToken
            ])->post(
                'https://orchestly.zoho.com/blueprint/api/buroserv/job', $postData);

        // Get Tenant from the host
        $get_http_host = request()->getHost();
        $tenant = strstr($get_http_host, '.', true);

        //Initialize the current tenant
        tenancy()->initialize($tenant);

        if ($response->successful()) {
            $response->json();

            $qntrlCard = new QntrlCard();
            $qntrlCard->qntrl_id = data_get($response->json(), 'id');
            $qntrlCard->buroflow_reference = data_get($response->json(), 'fields.customfield_shorttext22');
            $qntrlCard->scheduled_time = new Carbon($postData['duedate']);
            $qntrlCard->user_id = Auth::id();
            $qntrlCard->raw = $response->body();
            
            $qntrlCard->save();

            // Qntrl Order details for search.
            $qntrlOrder = new QntrlOrder();
            $qntrlOrder->raw = $response->body();
            $qntrlOrder->card_id = data_get($response->json(), 'id');
            $qntrlOrder->creation_date = new Carbon(data_get($response->json(), 'customfield_date3_utc'));
            $qntrlOrder->buroflow_reference = data_get($response->json(), 'fields.customfield_shorttext22');
            $qntrlOrder->location_id = data_get($response->json(), 'fields.customfield_shorttext14');
            $qntrlOrder->retail_account = data_get($response->json(), 'fields.customfield_shorttext16');
            $qntrlOrder->customer_name = data_get($response->json(), 'fields.customfield_shorttext21');
            $qntrlOrder->customer_reference = data_get($response->json(), 'fields.customfield_shorttext18');
            $qntrlOrder->prior_service = data_get($response->json(), 'fields.customfield_shorttext17');
            $qntrlOrder->radius_user = data_get($response->json(), 'fields.customfield_shorttext47');
            $qntrlOrder->aapt_service = data_get($response->json(), 'fields.customfield_shorttext35');

            $qntrlOrder->save();
        }
    
        return to_route('qntrl.index');
    }

    public function show($cardId)
    {
        $token = ZohoOauth::on('mysql')->latest()->first();
        $accessToken = 'Zoho-oauthtoken ' . $token->access_token;
        
        if ($cardId) {
            $response = Http::withHeaders([
                'Authorization' => $accessToken
                ])->get('https://orchestly.zoho.com/blueprint/api/buroserv/job/'.$cardId);

            if ($response->successful()) {
                $card = json_decode($response, true);
                $card = $card['job_details'];
                
                
            }
        }

        $orderStatus = $card['customfield_dropdown4'];
            switch($orderStatus)
            {
                case '31914000000046147':
                    $card['customfield_dropdown4'] =  'Submitted';
                    break;
                case '31914000000046148':
                    $card['customfield_dropdown4'] =  'Awaiting Transfer';
                    break;
                case '31914000000046149':
                    $card['customfield_dropdown4'] =  'Transfer Completed';
                    break;
                case '31914000000046150':
                    $card['customfield_dropdown4'] =  'Cancelled';
                    break;
                case '31914000000046151':
                    $card['customfield_dropdown4'] =  'Error';
                    break;
                case '31914000000046152':
                    $card['customfield_dropdown4'] =  'Closed';
                    break;
                default:
                    $card['customfield_dropdown4'] =  '';
            }

        $cardPlan = $card['customfield_dropdown6'];
        switch($cardPlan)
        {
            case '31914000000046203':
                $card['customfield_dropdown6'] = '25Mbps/5-10Mbps';
                break;
            case '31914000000046204':
                $card['customfield_dropdown6'] =  '25-50Mbps/5-20Mbps';
                break;
            case '31914000000046208':
                $card['customfield_dropdown6'] =  '25-100Mbps/5-40Mbps';
                break;
            case '31914000000046209':
                $card['customfield_dropdown6'] =  '100Mbps/40Mbps';
                break;
            case '31914000000046201':
                $card['customfield_dropdown6'] =  '12Mbps/1Mbps';
                break;
            case '31914000000046202':
                $card['customfield_dropdown6'] =  '25Mbps/5Mbps';
                break;
            case '31914000000046205':
                $card['customfield_dropdown6'] =  '25Mbps/10Mbps';
                break;
            case '31914000000046206':
                $card['customfield_dropdown6'] =  '50Mbps/20Mbps';
                break;
            case '31914000000046207':
                $card['customfield_dropdown6'] =  '100Mbps/20Mbps';
                break;
            case '31914000000046210':
                $card['customfield_dropdown6'] =  '250Mbps/25Mbps';
                break;
            case '31914000000046212':
                $card['customfield_dropdown6'] =  '250Mbps/100Mbps';
                break;
            case '31914000000046211':
                $card['customfield_dropdown6'] =  '500Mbps/200Mbps';
                break;
            case '31914000000046214':
                $card['customfield_dropdown6'] =  '1000Mbps/50Mbps';
                break;
            case '31914000000046213':
                $card['customfield_dropdown6'] =  '1000Mbps/400Mbps';
                break;
            case '31914000000046216':
                $card['customfield_dropdown6'] =  'FW Plus';
                break;
            default:
                $card['customfield_dropdown6'] =  '';
        }

        $cardTerm = $card['customfield_dropdown7'];
        switch($cardTerm)
        {
            case '31914000000046215':
                $card['customfield_dropdown7']  =  '0 Months';
                break;
            case '31914000000046218':
                $card['customfield_dropdown7']  =  '12 Months';
                break;
            case '31914000000046217':
                $card['customfield_dropdown7'] =  '24 Months';
                break;
            case '31914000000046219':
                $card['customfield_dropdown7'] =  '36 Months';
                break;
            case '31914000000046194':
                $card['customfield_dropdown7'] =  '48 Months';
                break;
            default:
                $card['customfield_dropdown7'] =  '';
        }

        return view('api.auth.qntrl.show', ['card' => $card]);
    }

    public function edit($cardId)
    {
        return view('api.auth.qntrl.edit', ['card' => $cardId]);
    }

    public function update(Request $request)   
    {
        $token = ZohoOauth::on('mysql')->latest()->first();
        $accessToken = 'Zoho-oauthtoken ' . $token->access_token;

        $cardId = $request['cardId'];
        
        $formData = [];
        if ($request['customfield_dropdown4'] != null) {
            $formData['customfield_dropdown4'] = $request['customfield_dropdown4'];

            $response = Http::asForm()->withHeaders([
            'Authorization' => $accessToken
            ])->post('https://orchestly.zoho.com/blueprint/api/buroserv/job/'.$cardId, $formData);

            if ($response->successful()) {
                $updateStatus = json_decode($response, true);
                $update_schedule = QntrlCard::where('qntrl_id', $updateStatus['id'])->first();
    
                if ($update_schedule != null) {
                    $update_schedule->status_updated = 'updated';
                    $update_schedule->save();
                }
            }
        }

        if ($request['due_date'] != null) {
            $show_date = new DateTime($request['due_date']);
            $formData['duedate'] = $show_date->format('Y-m-d\TH:i:sO');
            $newDate = $request['due_date'];

            $response = Http::asForm()->withHeaders([
            'Authorization' => $accessToken
            ])->post('https://orchestly.zoho.com/blueprint/api/buroserv/job/'.$cardId, $formData);

            if ($response->successful()) {
                $updateStatus = json_decode($response, true);
                $update_schedule = QntrlCard::where('qntrl_id', $updateStatus['id'])->first();
                
                if ($update_schedule != null)
                    {
                        $update_schedule->scheduled_time = new Carbon($newDate);
                        $update_schedule->user_id = Auth::id();
                        $update_schedule->save();
                    }
            }

        }

        return to_route('qntrl.show', $cardId);
    }
    public function delete($cardId)
    {
        return $cardId;
    }

    // Update Qntrl Card Order Status from Scheduler.
    public function QntrlScheduler($QntrlcardId)
    {
        // Create Zoho Access Token
        $token = ZohoOauth::on('mysql')->latest()->first();
        $accessToken = 'Zoho-oauthtoken ' . $token->access_token;
        // "Zoho-oauthtoken 1000.27cb28ac001d4f1b610f06c414fc5d5a.8fa8f34f61e4c2cc9c466e8aaccba395"

        // Update Qntrl Card Order Status through the Orchestly API.
        $response = Http::asForm()->withHeaders([
            'Authorization' => $accessToken
            ])->post(
                'https://orchestly.zoho.com/blueprint/api/buroserv/job/'.$QntrlcardId, 
                [
                'customfield_dropdown4' => '31914000000046148', // Set as Cancelled 31914000000046150 for testing. Should be Awaiting Transfer 31914000000046148 for production.
                ]);

        if ($response->successful()) {
            $orderStatus = json_decode($response, true);
            $orderUpdate = $orderStatus['id'];

            $scheduled_task = QntrlCard::where('qntrl_id', $orderStatus['id'])->first();
            $scheduled_task->status_updated = 'updated';
            $scheduled_task->save();
        }

                return view('api.auth.qntrl.show', ['card' => $QntrlcardId]);
    }

}
