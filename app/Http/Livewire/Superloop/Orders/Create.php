<?php

namespace App\Http\Livewire\Superloop\Orders;

use App\Http\Controllers\Api\Auth\QntrlController;

use Closure;
use Livewire\Component;
use Illuminate\Support\Arr;
use Illuminate\Support\HtmlString;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Event;
use App\Models\ServiceQualification;
use DateTimeImmutable;
use Filament\Resources\Forms\Components;
use App\Models\IMSRealms;
use App\Models\realm;
use App\Models\Reseller;
use App\Models\RadiusIP;
use App\Models\Tenant;
use App\Models\User;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ViewField;
use Filament\Forms\Concerns\InteractsWithForms;
use phpDocumentor\Reflection\Types\Integer;
use phpDocumentor\Reflection\Types\Null_;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class Create extends Component implements HasForms
{
    use InteractsWithForms;
    
    public $locId;
    public ServiceQualification $sq;
    public $data;
    public $realm_data;
    public $infrastructureNTD;
    public $infrastructureCPI;
    public $buroflowReference;

    public $customfield_date1;
    public $customfield_dropdown11;
    public $customfield_dropdown14;
    public $customfield_dropdown20;
    public $customfield_dropdown21;
    public $customfield_dropdown23;
    public $customfield_shorttext10;
    public $customfield_shorttext11;
    public $customfield_shorttext12;
    public $customfield_shorttext13;
    public $customfield_shorttext14;
    public $customfield_shorttext15;
    public $customfield_shorttext20;
    public $customfield_shorttext22;
    public $customfield_shorttext23;
    public $customfield_shorttext24;
    public $customfield_shorttext25;
    public $customfield_shorttext28;
    public $customfield_shorttext3;
    public $customfield_shorttext40;
    public $customfield_shorttext50;
    public $customfield_shorttext52;
    public $customfield_shorttext6;
    public $customfield_shorttext7;
    public $customfield_shorttext8;
    public $customfield_shorttext9;

    public $radiusIP_data;
    public $radiusIP_id;
    public $radius_password;
    public $ip_address;

    public $duedate;
    public $layout_id;
    public $record_owner;

    public $realm = '';
    public $realmId = null;

    public $resellerName;
    public $resellerId;

    public $ims_user;
    public $radUser = '';
    protected $radPass = '';
    protected $radIP = '';
    protected $radIPid = '';

    public function mount(Request $request, $locId): void
    {        
        $sq = ServiceQualification::on('tenant')->whereLocId($this->locId)->first();

        $associated_realm = Reseller::on('tenant')->pluck('associated_realm')->first();
        $vars = explode('|', $associated_realm);
        
        $realm_data = realm::on('mysql')->whereIn('realm_id', $vars)->pluck('realm_name', 'realm_name');

        $this->realm_data = $realm_data;
        $this->sq = $sq;
        $this->buroflowReference = date('ymdhis').'B';
        $this->radius_password = Str::random(8);
        $this->form->fill();

    }

    protected function getFormSchema(): array
    {
        return [
            Wizard::make([
                Wizard\Step::make('Customer Details')
                    ->icon('heroicon-o-user')
                    ->schema([
                        Hidden::make('customfield_dropdown9')
                            ->default('31914000000046195'),
                        TextInput::make('customfield_shorttext22')
                            ->default(
                                $this->buroflowReference
                            )
                            ->label('Buroflow Reference')
                            ->reactive()
                            ->afterStateUpdated(function (\Filament\Forms\Set $set, $state) {
                                $set($this->radUser, $state);
                            }
                            )
                            ->autofocus()
                            ->maxLength(200)
                            ->disabled()
                            ->columnSpan(4),
                        TextInput::make('customfield_shorttext28')
                            ->label('Authority Date')
                            ->default( Date('Y-m-d\TH:i:sP') )
                            ->disabled()
                            ->maxLength(50)
                            ->columnSpan(4),
                        TextInput::make('customfield_shorttext14')->required()
                            ->default(
                                data_get($this->sq->raw, 'locationId')
                            )
                            ->label('Location Id')
                            ->disabled()
                            ->columnSpan(3),

                        // Resellers

                        TextInput::make('reseller')->required()
                            ->default(
                                Reseller::on('tenant')->pluck('reseller_name')[0]
                            )
                            ->label('Reseller')
                            ->disabled()
                            ->columnSpan(6),

                        Hidden::make('resellerId')
                        ->default(
                            Reseller::on('tenant')->pluck('reseller_id')[0]
                        ), // customfield_shorttext40

                        TextInput::make('customfield_shorttext7') // Reseller Phone number
                            ->default(Reseller::on('tenant')->pluck('reseller_mobile')[0])
                            ->telRegex('((0|[+]61)[2,3,4,7,8]\d{8})|((1300|1800)\d{6})|((13)\d{4})|((\+)\d*)')
                            ->maxLength(12)
                            ->label('Reseller Phone')
                            ->helperText('Landline area code (02, 03 etc) or mobile (04). No spaces.')
                            ->columnSpan(2),
                        TextInput::make('customfield_shorttext25')->email() // Reseller Email address (required)
                            ->required()
                            ->default(User::on('tenant')->where('id',auth()->user()->id)->first()->email)
                            ->label('Reseller Email')
                            ->maxLength(50)
                            ->columnSpan(4),
                        
                        // Reseller Customer Details
                        TextInput::make('customer_reference') // Reseller's Customer Reference Number
                            ->label('Customer Reference')
                            ->maxLength(40)
                            ->columnSpan(3),
                        TextInput::make('customer_name') // Reseller's Customer Name
                            ->label('Customer Name')
                            ->maxLength(40)
                            ->columnSpan(3),
                        TextInput::make('retail_account') // Retail Account Number
                            ->label('Retail Account Number')
                            ->maxLength(40)
                            ->columnSpan(2),
                        TextInput::make('site_name') // Site Name
                            ->label('Site Name')
                            ->maxLength(40)
                            ->columnSpan(4),
                    ])
		    ->columns(12),

                // Order Details -- second page of the form
                Wizard\Step::make('Order Details')
                    ->icon('heroicon-o-clipboard')
                    ->schema([
                        Hidden::make('customfield_date5') // Qntrl Customer Authority date
                            ->default( Date('Y-m-d\TH:i:sO') )
                            ->label('customer_auth_date')
                            ->disabled(),
                        Hidden::make('layout_id')->required() // Qntrl Layout ID
                            ->default('31914000000046029')
                            ->disabled(),
                        Hidden::make('customfield_dropdown4') // Qntrl Order Status
                            ->default('31914000000046147') // Set as Error 31914000000046151 for testing. Should be 31914000000046147 Order Submitted on production.
                            ->disabled(),

                        // Address Details
                        // Unit Number
                        Hidden::make('customfield_shorttext20')
                            ->default(
                                data_get($this->sq->raw, 'addressDetails.unitNumber')
                            )
                            ->label('unitNumber')
			                ->disabled(),
		    	        // Street Number
                        Hidden::make('customfield_shorttext23')
                            ->default(
                                data_get($this->sq->raw, 'addressDetails.streetNumber')
                            )
                            ->label('streetNumber')
                            ->disabled(),
			            // Street Name
                        Hidden::make('customfield_shorttext9')
                            ->default(
                                data_get($this->sq->raw, 'addressDetails.street')
                            )
                            ->label('street')
                            ->disabled(),
                        // Suburb
                        Hidden::make('customfield_shorttext8')
                            ->default(
                                data_get($this->sq->raw, 'addressDetails.suburb')
                            )
                            ->label('suburb')
                            ->disabled(),
                        // State
                        Hidden::make('customfield_shorttext13')
                            ->default(
                                data_get($this->sq->raw, 'addressDetails.state')
                            )
                            ->label('state')
                            ->disabled(),
                        // Post Code
                        Hidden::make('customfield_shorttext11')
                            ->default(
                                data_get($this->sq->raw, 'addressDetails.postcode')
                            )
                            ->label('postcode')
                            ->disabled(),

                        // Location ID, Infrastructure and Port Select, and Technology Type.
                        TextInput::make('customfield_shorttext14')->required()
                            ->default(
                                data_get($this->sq->raw, 'locationId')
                            )
                            ->label('locationId')
                            ->disabled()
			                ->columnSpan(3),

                        TextInput::make('technologyType')->required()
                            ->default(
                                $this->technologyType()
                            )
                            ->label('technologyType')
                            ->disabled()
                            ->columnSpan(3),
                        Hidden::make('customfield_dropdown5')
                            ->default( $this->accessType() )
                            ->disabled(),
                        Select::make('infrastructureId')
                            ->label('Infrastructure Id')
                            ->options(
                                data_get($this->sq->raw, 'infrastructures.*.infrastructureId') 
                            )
                            ->required()
                            ->reactive()
                            ->placeholder("Select infrastructure")
                            ->columnSpan(4),
                        ViewField::make('portId')
                            ->label('portId')
                            ->view('superloop.orders.components.port-select')
                            ->columnSpan(12),

                        // Add Toggle
                        Hidden::make('show_radius')
                            ->default(true),
                        
                        // Radius User Details
                        TextInput::make('rad_user')
                            ->label('Radius Username')
                            ->requiredWith('show_radius')
                            ->default($this->buroflowReference)
                            ->columnSpan(3),
                        
                        // Realms
                        Select::make('realm')
                            ->label('Realm')
                            // ->default('@nbn.buroserv.com.au')
                            ->requiredWith('show_radius')
                            ->options($this->realm_data)
                            ->searchable()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {
                                $this->realm = $state ?? null;
                                
                                if ($this->realm) {
                                    $realmModel = realm::on('mysql')->where('realm_name', $this->realm)->first();
                                    $this->realmId = $realmModel ? $realmModel->realm_id : null;
                                } else {
                                    $this->realmId = null;
                                }
                                
                                $set('realmId', $this->realmId);
                            })
                            ->columnSpan(3),

                        Hidden::make('realmId')
                            ->default(57),
                            
                        // Radius Password
                        TextInput::make('rad_pass')
                            ->label('Radius Password')
                            ->requiredWith('show_radius')
                            ->default($this->radius_password)
                            ->columnSpan(2),
                        // Radius IP Address
                        TextInput::make('rad_ip')
                            ->label('Radius IP')
                            ->requiredWith('show_radius')
                            ->default($this->radiusIP()->ip_address)
                            ->disabled()
                            ->columnSpan(2),
                        // Radius I P S Table ID
                        Hidden::make('radiusIP_id')
                            ->default($this->radiusIP()->id),

                        // Set Order to External with Radius details
                        Hidden::make('customfield_dropdown3')
                            ->default('31914000000046146') // default set to external - 31914000000046146
                            ->requiredWith('show_radius')
                            ->hidden(
                                fn (\Filament\Forms\Get $get): bool => $get('show_radius') == false
                            ),
                    ])->columns(12),
                Wizard\Step::make('Delivery Details')
                    ->icon('heroicon-o-truck')
                    ->schema([
                        // Select NBN Speed and Contract Term
                        Select::make('customfield_dropdown6')->required()
                            ->label('NBN Speed')
                            ->options(
                                $this->getPlanOptions()
                            )
                            ->columnSpan(2),
                        Select::make('customfield_dropdown7')->required()
                            ->label('Contract Term')
                            ->options([
                                '31914000000046215' => '0 Months',
                                '31914000000046218' => '12 Months',
                                '31914000000046217' => '24 Months',
                                '31914000000046219' => '36 Months',
                                '31914000000046194' => '48 Months'
                            ])
                            ->default('31914000000046215')
				            ->columnSpan(2),
                        DateTimePicker::make('duedate')->required()
                            ->format('Y-m-d\TH:i:sO')
                            ->label('Connection Date')
                            ->minDate(today())
                            ->maxDate(now()->addMonth(3))
                            ->columnSpan(3),
                        
                        Hidden::make('aggregationMethod')
                            ->default('L2TP')
                    ])
                    ->columns(12)
            ])->submitAction(new HtmlString($this->loadingButton()))
        ];
    }

    protected function getFormStatePath(): string
    {
        return 'data';
    }

    public function render()
    {
        return view('livewire.superloop.orders.create');
    }

    private function loadingButton($label = "Submit", $loadingLabel = "Loading", $colour = "indigo")
    {
        return "
        <button class='inline-flex btn {$colour}' wire:loading.attr=\"disabled\" type=\"submit\" >
            <span wire:loading.remove>{$label}</span>
            <span wire:loading>{$loadingLabel}
                <x-loader class='inline-flex h-4 ml-3 align-middle' />
            </span>
        </button>";
    }

    public function submit()
    {
        $formState = $this->form->getState();
        $index = $formState['infrastructureId'];
        $label = data_get($this->sq->raw, "infrastructures.{$index}.infrastructureId");
        $formState['infrastructureId'] = $label;

        // Current ISP
        $isp = data_get($this->sq->raw, "infrastructures.{$index}");
        $portDetails = $this->form->getState();
        $serviceProvider = $portDetails['portId'] -1;

        // Select NTD or CPI
        $formState['ntdId'] = null;
        $formState['cpiId'] = null;
        if (Str::startsWith( $formState['infrastructureId'], "NTD" )) 
        { 
            $formState['ntdId'] = $formState['infrastructureId']; 
            $providerName = data_get($isp, "ntdPorts.{$serviceProvider}.referencedData.serviceProviderName");
        } 
        elseif (Str::startsWith( $formState['infrastructureId'], "CPI" ))
        {
            $formState['cpiId'] = $formState['infrastructureId'];
            $providerName = data_get($isp, 'referencedData.serviceProviderName');
        }

        // Set Service Provider Name
        $formState['serviceProviderName'] = $providerName;

        // dd($formState);

        // Submit form to QntrlController
        $request = new QntrlController();
        $data = $request->store($formState);
    }

    protected function getProductOptions()
    {
        $plans = isset($this->sq) ? data_get($this->sq->raw, 'availableProducts.*.options') : [];
        $options = [];
        foreach (Arr::flatten($plans) as $key => $plan) {
            $options[$plan] = $plan;
        }
        return $options ?? [];
    }

    protected function getPlanOptions()
    {
        $type = isset($this->sq) ? data_get($this->sq->raw, 'technologyType') : [];
        switch($type)
        {
            case 'FTTP':
                return ['31914000000046201' => '12Mbps/1Mbps', '31914000000046202' => '25Mbps/5Mbps', '31914000000046205' => '25Mbps/10Mbps', '31914000000046206' => '50Mbps/20Mbps', '31914000000046207' => '100Mbps/20Mbps', '31914000000046209' => '100Mbps/40Mbps', '31914000000046210' => '250Mbps/25Mbps', '31914000000046212' => '250Mbps/100Mbps', '31914000000046211' => '500Mbps/200Mbps', '31914000000046214' => '1000Mbps/50Mbps', '31914000000046213' => '1000Mbps/400Mbps'];
                break;
            case 'FTTN':
                return ['31914000000046201' => '12Mbps/1Mbps', '31914000000046202' => '25Mbps/5Mbps', '31914000000046203' => '25Mbps/5-10Mbps', '31914000000046204' => '25-50Mbps/5-20Mbps', '31914000000046207' => '100Mbps/20Mbps', '31914000000046208' => '25-100Mbps/5-40Mbps'];
                break;
            case 'FTTC':
                return ['31914000000046201' => '12Mbps/1Mbps', '31914000000046202' => '25Mbps/5Mbps', '31914000000046203' => '25Mbps/5-10Mbps', '31914000000046204' => '25-50Mbps/5-20Mbps', '31914000000046207' => '100Mbps/20Mbps', '31914000000046208' => '25-100Mbps/5-40Mbps'];
                break;
            case 'FTTB':
                return ['31914000000046201' => '12Mbps/1Mbps', '31914000000046202' => '25Mbps/5Mbps', '31914000000046203' => '25Mbps/5-10Mbps', '31914000000046204' => '25-50Mbps/5-20Mbps', '31914000000046207' => '100Mbps/20Mbps', '31914000000046208' => '25-100Mbps/5-40Mbps'];
                break;
            case 'HFC':
                return ['31914000000046201' => '12Mbps/1Mbps', '31914000000046202' => '25Mbps/5Mbps', '31914000000046205' => '25Mbps/10Mbps', '31914000000046206' => '50Mbps/20Mbps', '31914000000046207' => '100Mbps/20Mbps', '31914000000046209' => '100Mbps/40Mbps', '31914000000046210' => '250Mbps/25Mbps', '31914000000046214' => '1000Mbps/50Mbps'];
                break;
            case 'FW':
                return ['31914000000046201' => '12Mbps/1Mbps', '31914000000046202' => '25Mbps/5Mbps', '31914000000046216' => 'FW Plus'];
                break;
        }
    }

    protected function technologyType()
    {
        $type = isset($this->sq) ? data_get($this->sq->raw, 'technologyType') : [];
        switch($type)
        {
            case 'FTTP':
                return 'NFAS';
                break;
            case 'FTTN':
                return 'NCAS';
                break;
            case 'FTTC':
                return 'NCAS';
                break;
            case 'FTTB':
                return 'NCAS';
                break;
            case 'HFC':
                return 'NHAS';
                break;
            case 'FW':
                return 'NWAS';
                break;
        }
    }

    protected function accessType()
    {
        $type = isset($this->sq) ? data_get($this->sq->raw, 'technologyType') : [];
        switch($type)
        {
            case 'FTTP':
                return '31914000000046198';
                break;

            case 'FTTN':
                return '31914000000046197';
                break;

            case 'FTTC':
                return '31914000000046197';
                break;

            case 'FTTB':
                return '31914000000046197';
                break;
                
            case 'HFC':
                return '31914000000046200';
                break;
            case 'FW':
                return '31914000000046199';
                break;
        }
    }

    protected function radiusIP()
    {
        $this->radiusIP_data = RadiusIP::on('mysql')->where('buroflow_reference','')->first();

        return $this->radiusIP_data;
    }

    // protected function getRestorationSLAs()
    // {
    //     $slas = isset($this->sq) ? data_get($this->sq->raw, 'restorationSlas.*.sla') : [];
    //     $options = [];
    //     foreach (Arr::flatten($slas) as $key => $sla) {
    //         $options[$sla] = $sla;
    //     }

    //     return $options ?? [];
    // }

    protected function getPorts()
    {
        $ports = isset($this->sq) ? data_get($this->sq->raw, 'infrastructures.*.ntdPorts') : [];

        $options = [];
        foreach (Arr::flatten($ports) as $key => $port) {
            $options[$port] = $port;
        }

        return $options ?? [];
        // return $ports;
    }
}
