<?php

namespace App\Http\Livewire\Mobileplans;

use Livewire\Component;
use Filament\Forms\Components;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use App\Models\ServiceQualification;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use App\Http\Integrations\APIHub\Requests\SuperloopLocationQualificationRequest;
use App\Models\Tenant;
use App\Models\VendorProduct;
use App\Models\Customer;
use App\Models\SimCard;
use App\Models\CustomerContactInfo;
use App\Models\Reseller;
use App\Models\User;
use App\Models\ResellerWholesalePackage;
use App\Models\RetailPackage;
use App\Models\WholesalePackage;
use App\Models\RetailPackageOption;
use App\Models\WholesalePackageOption;
use App\Models\CustomerAddress;
use Redirect;
use Filament\Forms\Components\Card;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Section;
use App\Http\Controllers\MobileplanController;
use App\Http\Controllers\OrderController;
use Filament\Notifications\Notification;
use Illuminate\Support\HtmlString;
use Filament\Forms\Set;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Button;
use Closure;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\ViewField;
use Filament\Forms\Components\Actions;
use Illuminate\Support\Facades\Blade;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Placeholder;

use Illuminate\Http\Request;
use App\Models\Order;
// use App\Models\SimCard;
use Illuminate\Support\Str;
use Auth;
// use App\Models\Customer;
// use App\Models\CustomerAddress;
// use App\Models\CustomerContactInfo;
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


class Assign extends Component implements HasForms
{
    use InteractsWithForms;


    public $account_name;
    public $cost_centre;
    public $retail_package_id;
    public $retail_package_option_id;
    public $company_id;
    public $plan_name;
    public $job_title;
    public $customer_id;
    public $carrier;
    public $customer_name;
    public $customer_link;
    public $mobile_number;
    public $wholesale_or_retail;
    public $vendor_id;
    public $mobile_plan_id;
    public $shipvia_id;
    public $line_one;
    public $line_two;
    public $line_three;
    public $city;
    public $state;
    public $country;
    public $postal_code;
    public $is_billing =true;
    public $small_balance_allow;
    public $date_of_birth;
    public $email;
    public $first_name;
    public $mid_name;
    public $last_name;
    public $website;
    public $gender='male';
    public $title='mr';
    public $current_phone_number;
    public $primary_contact_name;
    public $retail_customer;
    public $content;
    public $reseller_id;
    public $wholesale_package_id;
    public $wholesale_package_option_id;
    public $retails_discount_mrc_dollar='';
    public $retails_discount_mrc_precentage='';

    public $show_reseller_packages = false;
    public $show_reseler_dropdown=false;
    public $show_reseller_retail_packages=false;
    public $show_reseller_wholesale_options_packages = false;
    public $show_reseller_retail_options_packages = false;
    public $wholesale_pakage_id;
    public $retail_pakage_id;

    public $is_retails_discount_mrc_dollar_disabled = false;
    public $is_retails_discount_mrc_precentage_disabled = false;

    public $user;

    public $show_port_details=true;
    public $show_new_number_message=false;

    public $show_new_customer=false;

    public $port_or_new_number='port';
    public $existing_or_new_customer='existing';

    public function mount(): void
    {
        $this->user =User::find(auth()->user()->id);
        if($this->user->tenant_role === 'admin'){
            $this->show_reseler_dropdown=true;
        }
        $this->form->fill([
            'retails_discount_mrc_dollar' => $this->retails_discount_mrc_dollar,
            'retails_discount_mrc_precentage' => $this->retails_discount_mrc_precentage,
        ]);
        // $this->reseller_id = 0;
        // dd(Customer::where('reseller_id',auth()->user()->id)->with('customercontactinfos')->get()->pluck('customercontactinfos.display_name','id'));
    }

    public function onResellerSelectChange($state)
    {
        if($state != null){
            $this->reseller_id = $state;
            $this->show_reseller_packages = ! $this->show_reseller_packages;
        }else{
            $this->reseller_id = 0;
            $this->show_reseller_packages = false;
        }
    }

    public function onResellerWholesaleSelectChange($state)
    {
        if($state != null){
            $this->wholesale_pakage_id = $state;
            $this->show_reseller_retail_packages = ! $this->show_reseller_retail_packages;
            $this->show_reseller_wholesale_options_packages = ! $this->show_reseller_wholesale_options_packages;
        }else{
            $this->wholesale_pakage_id = 0;
            $this->show_reseller_retail_packages = false;
            $this->show_reseller_wholesale_options_packages = false;
        }
    }

    public function onResellerRetailsSelectChange($state)
    {
        if($state != null){
            $this->retail_pakage_id = $state;
            $this->show_reseller_retail_options_packages = ! $this->show_reseller_retail_options_packages;
        }else{
            $this->retail_pakage_id = 0;
            $this->show_reseller_retail_options_packages = false;
        }
    }


    protected function getFormSchema(): array
    {
        return [
            Wizard::make([
                Wizard\Step::make('Plan Details')
                    ->icon('gmdi-account-tree-r')
                    ->columns(3)
                        ->schema([
                            Forms\Components\Select::make('reseller_id')
                            ->label("Reseller")
                            ->required()
                            ->searchable()
                            ->reactive()
                            ->hidden(fn (Closure $get): bool => $get('show_reseler_dropdown') == false)
                            ->afterStateUpdated(fn ($state) => $this->onResellerSelectChange($state))
                            ->options(function () {
                                return  Reseller::all()->pluck('reseller_name','reseller_id');;
                            }),

                            Forms\Components\Select::make('mobile_plan_id')
                            ->label("Vendor Package")
                            ->required()
                            ->searchable()
                            ->disablePlaceholderSelection()
                            ->options(function () {
                                return  VendorProduct::all()->pluck('vendor_product_name','id');;
                            }),
                            // Forms\Components\Select::make('shipvia_id')
                            //     ->label("Carrier")
                            //     // ->required()
                            //     ->default('1')
                            //     // ->hint('[Forgotten your password?](forgotten-password)')
                            //     // ->helperText('Your full name here, including any middle names.')
                            //     ->options([
                            //         '1' => 'Telstra',
                            //         '2' => 'Symbio',
                            // ]),
                            Forms\Components\Select::make('cost_centre')
                                ->label("Cost Centre")
                                ->required()
                                ->default('default')
                                ->options([
                                    'default' => 'Default'
                                ]),
                            Card::make()
                                ->hidden(fn (Closure $get): bool => $get('show_reseller_packages') == false)
                                ->columns(3)
                                ->schema([
                                    Forms\Components\Select::make('wholesale_package_id')
                                        ->label("Wholesale Package")
                                        ->searchable()
                                        ->required()
                                        ->reactive()
                                        ->afterStateUpdated(fn ($state) => $this->onResellerWholesaleSelectChange($state))
                                        ->options(function () {
                                            return  WholesalePackage::where('reseller_id',$this->reseller_id)->pluck('wholesale_pakage_name','id');;
                                        })->hidden(fn (Closure $get): bool => $get('show_reseller_packages') == false),
                                    Forms\Components\Select::make('wholesale_package_option_id')
                                        ->label("Wholesale Package Option")
                                        ->searchable()
                                        ->disablePlaceholderSelection()
                                        ->options(function () {
                                            return  WholesalePackageOption::where('wholesale_pakage_id',$this->wholesale_pakage_id)->pluck('wholesale_pakage_option_name','id');;
                                        })->hidden(fn (Closure $get): bool => $get('show_reseller_wholesale_options_packages') == false),
                            ]),

                            Card::make()
                                ->hidden(fn (Closure $get): bool => $get('show_reseller_retail_packages') == false)
                                ->columns(3)
                                ->schema([
                                    Forms\Components\Select::make('retail_package_id')
                                        ->label("Retail Package")
                                        ->searchable()
                                        ->reactive()
                                        ->afterStateUpdated(fn ($state) => $this->onResellerRetailsSelectChange($state))
                                        ->options(function () {
                                            return  RetailPackage::where('reseller_id',$this->reseller_id)->pluck('retail_pakage_name','id');;
                                        })->hidden(fn (Closure $get): bool => $get('show_reseller_retail_packages') == false),
                                    Forms\Components\Select::make('retail_package_option_id')
                                        ->label("Retail Package Option")
                                        ->searchable()
                                        ->disablePlaceholderSelection()
                                        ->options(function () {
                                            return  RetailPackageOption::where('retail_package_id',$this->retail_pakage_id)->pluck('retail_pakage_option_name','id');;
                                        })->hidden(fn (Closure $get): bool => $get('show_reseller_retail_options_packages') == false),
                                    Forms\Components\TextInput::make('retails_discount_mrc_dollar')
                                        ->label("Retail MRC Discount $")
                                        ->disabled(fn () => !empty($this->is_retails_discount_mrc_precentage_disabled) )
                                        ->reactive()
                                        ->rule('integer')
                                        ->numeric()
                                        ->afterStateUpdated(fn ($state) => $this->updatedRetailDiscountMRCDollarDollar($state))
                                        ->hidden(fn (Closure $get): bool => $get('show_reseller_retail_packages') == false),
                                    Forms\Components\TextInput::make('retails_discount_mrc_precentage')
                                        ->label("Retail MRC Discount %")
                                        ->disabled(fn () => !empty($this->is_retails_discount_mrc_dollar_disabled))
                                        ->reactive()
                                        ->rule('integer')
                                        ->numeric()
                                        ->afterStateUpdated(fn ($state) => $this->updatedRetailDiscountMRCDollarPrecentate($state))
                                        ->hidden(fn (Closure $get): bool => $get('show_reseller_retail_packages') == false),
                                ])


                        ]),
                Wizard\Step::make('Customer Details')
                    ->icon('polaris-minor-customers-filled')
                    ->columns(1)
                        ->schema([
                            Forms\Components\Radio::make('existing_or_new_customer')
                                ->label('Search Existing Customer or New Customer ?')
                                ->options([
                                    'existing' => 'Existing Customer',
                                    'new' => 'New Customer'
                                ])
                                ->default('existing')
                                ->reactive()
                                ->afterStateUpdated(fn ($state) => $this->onExistingOrNewCustomer($state))
                                ->inline(),

                            Grid::make(2)
                                ->hidden(fn (Closure $get): bool => $get('show_new_customer') == true)
                                ->schema([
                                    Forms\Components\Select::make('customer_id')
                                        ->label("Customer")
                                        ->required()
                                        ->hintColor('primary')
                                        ->disablePlaceholderSelection()
                                        // ->hintIcon('heroicon-s-plus-circle')
                                        // ->hint(new HtmlString('<a href="#" wire:click="show_reseller_packagesCustomerCreateInputs"><b>create customer</b></a>'))
                                        ->searchable()
                                        ->options(function () {

                                            // return  Customer::where('reseller_id',auth()->user()->id)->where('disable_account',0)->with('customercontactinfos')->get()->pluck('customercontactinfos.display_name','id');
                                            return  Customer::where('reseller_id',65)->where('disable_account',0)->with('customercontactinfos')->get()->mapWithKeys(function ($customer) {
                                                if($customer->customercontactinfos !== null && $customer->customercontactinfos->first_name !== null){
                                                     return [$customer->id => $customer->customer_code . ' - ' . $customer->customercontactinfos->first_name.' '.$customer->customercontactinfos->last_name];

                                                }
                                            });
                                    }),
                                    Forms\Components\Select::make('vendor_id')
                                        ->label("Account Name")
                                        ->required()
                                        ->default('1')
                                        // ->hint('[Forgotten your password?](forgotten-password)')
                                        // ->helperText('Your full name here, including any middle names.')
                                        ->options([
                                            '1' => '60000500 Aussiecom',
                                    ]),
                                ])->columns(2),
                            Grid::make(2)
                                ->hidden(fn (Closure $get): bool => $get('show_new_customer') == false)
                                ->schema([
                                    Fieldset::make('Customer Details')
                                        ->schema([
                                            Forms\Components\TextInput::make('first_name')
                                            ->label("First Name")
                                            ->required()
                                            ->autofocus()
                                            ->columnSpan(1),
                                            Forms\Components\TextInput::make('mid_name')
                                                ->label("Mid Name")
                                                ->required()
                                                ->autofocus()
                                                ->columnSpan(1),
                                            Forms\Components\TextInput::make('last_name')
                                                ->label("Last Name")
                                                ->required()
                                                ->autofocus()
                                                ->columnSpan(1),
                                            Forms\Components\Select::make('gender')
                                                ->label("Gender")
                                                ->required()
                                                ->default('male')
                                                ->options([
                                                    'female' => 'Female',
                                                    'male' => 'Male',
                                                ]),
                                            Forms\Components\Select::make('title')
                                                ->label("Gender")
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
                                                ->required()
                                                // ->regex('/^.+@.+$/i')
                                                ->autofocus()
                                                ->columnSpan(1),
                                            Forms\Components\TextInput::make('current_phone_number')
                                                ->label('Current Phone Number')
                                                ->autofocus(),
                                            Forms\Components\DatePicker::make('date_of_birth')
                                                ->label('Date of Birth')
                                                ->required()
                                                ->minDate(now()->subYears(50))
                                                ->maxDate(now()->subYears(18))
                                                ->default(now()->subYears(18))
                                            ])
                                        ->columns(4),
                                        Fieldset::make('Address Details')
                                        ->schema([
                                            Forms\Components\TextInput::make('line_one')
                                                ->label("Address Line One")
                                                ->required()
                                                ->autofocus()
                                                ->columnSpan(1),
                                            Forms\Components\TextInput::make('line_two')
                                                ->label("Address Line Two")
                                                ->required()
                                                ->autofocus()
                                                ->columnSpan(1),
                                            Forms\Components\TextInput::make('line_three')
                                                ->label("Address Line Three")
                                                // ->required()
                                                ->autofocus()
                                                ->columnSpan(1),
                                            Forms\Components\TextInput::make('city')
                                                ->label("City")
                                                ->required()
                                                ->autofocus()
                                                ->columnSpan(1),
                                            Forms\Components\TextInput::make('state')
                                                ->label("State")
                                                ->required()
                                                ->autofocus()
                                                ->columnSpan(1),
                                            Forms\Components\TextInput::make('country')
                                                ->label("country")
                                                ->required()
                                                ->autofocus()
                                                ->columnSpan(1),
                                            Forms\Components\TextInput::make('postal_code')
                                                ->label("Postal Code")
                                                ->required()
                                                ->autofocus()
                                                ->columnSpan(1),
                                            ])
                                        ->columns(4),
                                        Fieldset::make('Account Settings')
                                        ->schema([
                                            Forms\Components\Select::make('vendor_id')
                                            ->label("Account Name")
                                            // ->required()
                                            ->default('1')
                                            // ->hint('[Forgotten your password?](forgotten-password)')
                                            // ->helperText('Your full name here, including any middle names.')
                                            ->options([
                                                '1' => '60000500 Aussiecom',
                                            ]),
                                            Forms\Components\Toggle::make('is_billing')->label("Set As Billing Address")->inline(false),
                                            ])
                                        ->columns(2),
                                ])->columns(2),
                        ]),
                Wizard\Step::make('Port OR New Number')
                    ->icon('gmdi-sim-card')
                    ->columns(1)
                        ->schema([
                            Forms\Components\Radio::make('port_or_new_number')
                                ->label('Port OR New Number ?')
                                ->options([
                                    'port' => 'Port a Number',
                                    'new' => 'Need a new Number'
                                ])
                                ->default('port')
                                ->reactive()
                                ->afterStateUpdated(fn ($state) => $this->onPortOrNewNumber($state))
                                ->inline(),

                            Grid::make(3)
                                ->hidden(fn (Closure $get): bool => $get('show_port_details') == false)
                                ->schema([
                                    Forms\Components\TextInput::make('mobile_number')
                                    ->label("Mobile Number to Port")
                                    // ->required()
                                    ->autofocus()
                                    ->columnSpan(1),
                                    Forms\Components\TextInput::make('loosing_carrier')
                                        ->label("Losing Carrier")
                                        // ->required()
                                        ->autofocus()
                                        ->columnSpan(1),
                                    Forms\Components\TextInput::make('loosing_carrier_retail_account')
                                        ->label("Losing Carrier Retail Account")
                                        // ->required()
                                        ->autofocus()
                                        ->columnSpan(1),
                                ])->columns(3),

                            Grid::make(1)
                                ->hidden(fn (Closure $get): bool => $get('show_port_details') == true)
                                ->schema([
                                    Placeholder::make('')
                                ->content(new HtmlString('<div style="background-color: #FFD86B; padding: 20px; ">
                                <h1><b>New Number</b></h1>
                                <p>A new sim card and Mobile number will be allocated to this order and you will be notified about the details ASAP.</p>
                            </div>'))
                                ]),


                ]),
            ])
            ->submitAction(new HtmlString(Blade::render(<<<BLADE
            <x-filament::button
                type="submit"
                size="sm"
            >
                Submit
            </x-filament::button>


            BLADE)))
        ];
    }

    public function onExistingOrNewCustomer($state)
    {
        if($state != null){
            if($state == "new"){
                $this->show_new_customer = true;
            }else{
                $this->show_new_customer = false;
            }
        }else{
            $this->is_single_add = false;
        }
    }

    public function onPortOrNewNumber($state)
    {
        if($state != null){
            if($state == "new"){
                $this->show_port_details = false;
                $this->show_new_number_message = true;
            }else{
                $this->show_port_details = true;
                $this->show_new_number_message = false;
            }
        }else{
            $this->is_single_add = false;
        }
    }

    public function submit()
    {

        $data = $this->form->getState();
        // $request = new OrderController();
        // // $state['wholesale_or_retail'] = $this->checkWholesale($state['wholesale_or_retail']);
        // $data = $request->store($state);

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
            $customer_address = CustomerAddress::where('customer_id',$data['customer_id'])->first();
            $customer_contact_info = CustomerContactInfo::where('customer_id',$data['customer_id'])->first();
            // dd($customer_address);

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
                'line_three' => $customer_address->line_three,
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
            // 'simNo' => $latest_simcard->sim_card_code,
            'simNo' => '491703534',
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
            dd($reserve_mobile_return);

            if ($reserve_mobile_return->errorCode === 0){
                Notification::make()
                ->title('Success')
                ->duration(20000)
                ->body('This is the reserve mobile number: ' . $reserve_mobile_return->reservedPooledResources->resourceValue)
                ->status('success') // This sets the notification to be an error notification
                ->send();
            }
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
                            $headers = [
                                'Content-Type' => 'application/json',
                                'Authorization' => 'Bearer '.env('SELCOMM_BU_TOKEN')
                            ];
                            $client = new Client(
                                [
                                    'headers' => $headers
                                ]
                            );

                            if($data['existing_or_new_customer'] === "new"){



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
                                            $customer->reseller_id = $data["reseller_id"];
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
                                            ->body('order successfully created')
                                            ->status('success') // This sets the notification to be an error notification
                                            ->send();

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
                        $order->cost_centre = $data["cost_centre"];
                        $order->customer_id = $customer_id;
                        $order->wholesale_package_id = $data["wholesale_package_id"];
                        $order->wholesale_package_option_id = $data["wholesale_package_option_id"];
                        $order->retail_package_id = $data["retail_package_id"];
                        $order->retail_package_option_id = $data["retail_package_option_id"];
                        $order->retails_discount_mrc_dollar = $data["retails_discount_mrc_dollar"];
                        $order->retails_discount_mrc_precentage = $data["retails_discount_mrc_precentage"];
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




                        // return to_route('orders.index');


                    } // octane order create request success end
                    // session()->flash('error', 'Your custom error message.');
                    if ($order_create_response_return->errorCode !== 0){
                        Notification::make()
                        ->title('Error')
                        ->duration(20000)
                        ->body('Order not created, an error occurred: ' . $order_create_response_return->errorMessage)
                        ->status('danger') // This sets the notification to be an error notification
                        ->send();
                    }

                    // dd('oooooo');
                    // return to_route('mobileplans.assign');
                } // select mobile reqest end
                if ($select_mobile_return->errorCode !== 0){
                    Notification::make()
                    ->title('Error')
                    ->duration(20000)
                    ->body('An error occurred: ' . $select_mobile_return->errorMessage)
                    ->status('danger') // This sets the notification to be an error notification
                    ->send();
                }


            } // reserve mobile request end
            if ($reserve_mobile_return->errorCode !== 0){
                Notification::make()
                ->title('Error')
                ->duration(20000)
                ->body('An error occurred: ' . $reserve_mobile_return->errorMessage)
                ->status('danger') // This sets the notification to be an error notification
                ->send();
            }


        }// simcard status soap end

        if ($simcard_status_return->errorCode !== 0){
            Notification::make()
            ->title('Error')
            ->duration(20000)
            ->body('An error occurred: ' . $simcard_status_return->errorMessage)
            ->status('danger') // This sets the notification to be an error notification
            ->send();
        }



    }


    private function checkWholesale($value){
        if($value == '0'){
            return 0;
        }
        return 1;
    }

    public function updatedRetailDiscountMRCDollarDollar($value)
    {
        if(!empty($value)){
            $this->is_retails_discount_mrc_dollar_disabled = true;
        }else{
            $this->is_retails_discount_mrc_dollar_disabled = false;
        }
    }

    public function updatedRetailDiscountMRCDollarPrecentate($value)
    {
        if(!empty($value)){
            $this->is_retails_discount_mrc_precentage_disabled = true;
        }else{
            $this->is_retails_discount_mrc_precentage_disabled = false;
        }
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
        return view('livewire.mobileplans.assign');
    }
}
