<?php

namespace App\Http\Livewire\Customers;

use Livewire\Component;
use Filament\Forms\Components;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use App\Models\ServiceQualification;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use App\Http\Integrations\APIHub\Requests\SuperloopLocationQualificationRequest;
use App\Models\Tenant;
use App\Models\User;
use Redirect;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Section;
use App\Http\Controllers\CustomerController;
use Filament\Notifications\Notification;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Facades\Blade;
use Carbon\Carbon;
use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Models\CustomerContactInfo;
use Illuminate\Support\Str;
use Auth;
use Illuminate\Support\Facades\Http;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Requests\StoreCustomerRequest;
use GuzzleHttp\Client;
use App\Models\Reseller;
use Filament\Notifications\NotificationManager;

class Create extends Component implements HasForms
{
    use InteractsWithForms;


    public $email;
    public $first_name;
    public $mid_name;
    public $last_name;
    public $website;
    public $gender='male';
    public $title='mr';
    public $current_phone_number;
    public $company_id;
    public $primary_contact_name;
    public $job_title;
    public $allow_override_rate;
    public $payments_allowed;
    public $auto_apply_payments;
    public $print_statements;
    public $send_statement_by_email;
    public $shared_credit_policy;
    public $consolidate_statements;
    public $fin_change_apply;
    public $line_one;
    public $line_two;
    public $line_three;
    public $reseller_id;
    public $city;
    public $state;
    public $country;
    public $postal_code;
    public $is_billing =true;
    public $small_balance_allow;
    public $date_of_birth;
    public $home_phone;
    public $paper_bill=false;
    public $email_bill=true;

    public function mount(): void
    {
        if (tenant()) {
            $this->tenant_id = tenant()->id;
        }
    }



    protected function getFormSchema(): array
    {
        return [
            Wizard::make([
                Wizard\Step::make('Retail Customer Details')
                    ->icon('polaris-minor-customers-filled')
                    ->columns(4)
                    ->schema([
                        Forms\Components\TextInput::make('first_name')
                            ->label("First Name")
                            // ->required()
                            ->autofocus()
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('mid_name')
                            ->label("Mid Name")
                            // ->required()
                            ->autofocus()
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('last_name')
                            ->label("Last Name")
                            // ->required()
                            ->autofocus()
                            ->columnSpan(1),
                        Forms\Components\Select::make('gender')
                            ->label("Gender")
                            // ->required()
                            ->default('male')
                            ->options([
                                'female' => 'Female',
                                'male' => 'Male',
                            ]),
                        Forms\Components\Select::make('title')
                            ->label("Title")
                            // ->required()
                            ->default('basic')
                            ->options([
                                'mr' => 'Mr',
                                'mrs' => 'Mrs',
                                'miss' => 'Miss',
                            ]),
                        Forms\Components\TextInput::make('website')
                            ->label("Website")
                            // ->required()
                            ->autofocus()
                            ->columnSpan(1),
                        // Forms\Components\Select::make('company_id')
                        //     ->label("Company")
                        //     // ->required()
                        //     ->default('basic')
                        //     ->options([
                        //         '0' => 'Bitzify Sri Lanka',
                        //         '1' => 'Buroserv Australia',
                        //         '2' => 'Bitzify Australia',
                        //     ]),
                        Forms\Components\TextInput::make('primary_contact_name')
                            ->label("Primary Contact Name")
                            ->autofocus()
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('job_title')
                            ->label("Job Title")
                            ->autofocus()
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('email')
                            ->label('Email Address')
                            ->email()
                            // ->required()
                            ->autofocus()
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('current_phone_number')
                            ->label('Current Phone Number')
                            ->autofocus(),
                        Forms\Components\TextInput::make('home_phone')
                            ->label('Home Phone Number')
                            ->autofocus(),
                        Forms\Components\DatePicker::make('date_of_birth')
                            ->label('Date of Birth')
                            ->default(now())
                    ]),
                Wizard\Step::make('Address Details')
                    ->icon('entypo-address')
                    ->columns(4)
                    ->schema([
                    Forms\Components\TextInput::make('line_one')
                        ->label("Address Line One")
                        // ->required()
                        ->autofocus()
                        ->columnSpan(1),
                    Forms\Components\TextInput::make('line_two')
                        ->label("Address Line Two")
                        // ->required()
                        ->autofocus()
                        ->columnSpan(1),
                    Forms\Components\TextInput::make('line_three')
                        ->label("Address Line Three")
                        // ->required()
                        ->autofocus()
                        ->columnSpan(1),
                    Forms\Components\TextInput::make('city')
                        ->label("City")
                        // ->required()
                        ->autofocus()
                        ->columnSpan(1),
                    Forms\Components\TextInput::make('state')
                        ->label("State")
                        // ->required()
                        ->autofocus()
                        ->columnSpan(1),
                    Forms\Components\TextInput::make('country')
                        ->label("country")
                        // ->required()
                        ->autofocus()
                        ->columnSpan(1),
                    Forms\Components\TextInput::make('postal_code')
                        ->label("Postal Code")
                        // ->required()
                        ->autofocus()
                        ->columnSpan(1),
                        Forms\Components\Toggle::make('is_billing')->label("Set As Billing Address")->inline(false),
                ]),
                Wizard\Step::make('Reseller Details')
                    ->icon('entypo-man')
                    ->columns(4)
                    ->schema([
                        Forms\Components\Select::make('reseller_id')
                        ->label("Reseller")
                        ->required()
                        ->searchable()
                        ->reactive()
                        ->options(function () {
                            return  Reseller::all()->pluck('reseller_name','reseller_id');;
                        }),
                ]),
                Wizard\Step::make('Account Settings')
                    ->icon('feathericon-settings')
                    ->columns(6)
                    ->schema([
                        Forms\Components\Toggle::make('allow_override_rate')->inline(false)->default(false),
                        Forms\Components\Toggle::make('payments_allowed')->inline(false),
                        Forms\Components\Toggle::make('auto_apply_payments')->inline(false),
                        Forms\Components\Toggle::make('print_statements')->inline(false),
                        Forms\Components\Toggle::make('send_statement_by_email')->inline(false),
                        Forms\Components\Toggle::make('shared_credit_policy')->inline(false),
                        Forms\Components\Toggle::make('consolidate_statements')->inline(false),
                        Forms\Components\Toggle::make('fin_change_apply')->inline(false),
                        Forms\Components\Toggle::make('small_balance_allow')->inline(false),
                        Forms\Components\Toggle::make('paper_bill')->inline(false),
                        Forms\Components\Toggle::make('email_bill')->inline(false),
                ])
            ])
            ->submitAction(new HtmlString(Blade::render(<<<BLADE
            <x-filament::button
                type="submit"
                size="sm"
                wire:loading.attr="disabled"
            >
                Submit
                <span wire:loading>Processing...</span>
            </x-filament::button>
        BLADE)))


        ];
    }

    public function submit()
    {
        $data = $this->form->getState();

        $data['allow_override_rate'] = $this->checkToggleData($data['allow_override_rate']);
        $data['payments_allowed'] = $this->checkToggleData($data['payments_allowed']);
        $data['auto_apply_payments'] = $this->checkToggleData($data['auto_apply_payments']);
        $data['print_statements'] = $this->checkToggleData($data['print_statements']);
        $data['send_statement_by_email'] = $this->checkToggleData($data['send_statement_by_email']);
        $data['shared_credit_policy'] = $this->checkToggleData($data['shared_credit_policy']);
        $data['consolidate_statements'] = $this->checkToggleData($data['consolidate_statements']);
        $data['fin_change_apply'] = $this->checkToggleData($data['fin_change_apply']);
        $data['small_balance_allow'] = $this->checkToggleData($data['small_balance_allow']);
        $data['is_billing'] = $this->checkToggleData($data['is_billing']);
        $data['paper_bill'] = $this->checkToggleData($data['paper_bill']);
        $data['email_bill'] = $this->checkToggleData($data['email_bill']);
        $birth_date = Carbon::parse($data["date_of_birth"]);

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
                  "Type": "Person",
                  "SubTypeId": "",
                  "StatusId": "I",
                  "Name": "'.$data['first_name'].'  '.$data['last_name'].'",
                  "FirstName": "'.$data['first_name'].'",
                  "DateOfBirth": "'.$birth_date->format('Y-m-d\TH:i:s').'",
                  "Gender": "'.$data["gender"].'",
                  "Title": "'.$data["title"].'",
                  "TradingName": "'.$data["first_name"].'",
                  "BusinessNumber": "69131636836",
                  "Email": "'.$data["email"].'",
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

                  if($array['Id'] > 0){
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
                    $customer->reseller_id =  $data["reseller_id"];
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

                    Notification::make()
                    ->title('Success')
                    ->duration(20000)
                    ->body('customer successfully created')
                    ->status('success') // This sets the notification to be an error notification
                    ->send();
                  }else{
                    Notification::make()
                    ->title('Error')
                    ->duration(20000)
                    ->body('data not saved in DB')
                    ->status('danger') // This sets the notification to be an error notification
                    ->send();
                  }
                    return to_route('customers.index');

          }catch (\Exception $e) {

              $error_message = 'API call failed';
              if ($e->hasResponse()) {
                  $response = json_decode($e->getResponse()->getBody()->getContents(), true);
                  $error_message = $response['message'] ?? $error_message;

              }
              Notification::make()
              ->title('Error')
              ->duration(20000)
              ->body($error_message)
              ->status('danger') // This sets the notification to be an error notification
              ->send();
            //   return view('customers.create')->with('error', $error_message);

          }





    }


    private function checkToggleData($value){
        if($value == null){
            return 0;
        }
        return 1;
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('User updated')
            ->body('The user has been saved successfully.');
    }

    public function render()
    {
        return view('livewire.customers.create');
    }
}
