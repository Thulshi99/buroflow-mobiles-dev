<?php

namespace App\Http\Livewire\Superloop\Orders;

use App\Filament\Resources\QntrlCardResource\Pages\CreateQntrlCard;
use App\Http\Controllers\Api\Auth\QntrlController;
use App\Http\Integrations\Zoho\Requests\QntrlCreateCardRequest;
use Closure;
use Livewire\Component;
use Illuminate\Support\Arr;
use Illuminate\Support\HtmlString;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Collection;

use App\Models\ServiceQualification;
use DateTimeImmutable;
use Filament\Resources\Forms\Components;
use App\Models\IMSRealms;
use App\Models\realm;
use App\Models\Reseller;
use App\Models\Tenant;
use App\Models\User;
use App\Services\TenantService;
use App\Models\RadiusIP;

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
use DB;
use Stancl\Tenancy\TenantManager;

class AdminCreate extends Component implements HasForms
{
    use InteractsWithForms;

    public $locId;
    public ServiceQualification $sq;
    public $data;
    public $infrastructureNTD;
    public $infrastructureCPI;
    public $buroflowReference;
    public $resellers;

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
        $resellers = $this->fetchResellers();

        $tenant = Tenant::where('id', 'admin')->first();
        TenantService::switchToTenant($tenant);

        $sq = ServiceQualification::on('tenant')->whereLocId($this->locId)->first();

        $this->sq = $sq;
        $this->resellers = $resellers;
        $this->buroflowReference = date('ymdhis').'B';
        $this->radius_password = Str::random(8);
        $this->form->fill();
    }

    protected function fetchResellers()
    {
        $collection = new Collection();

        foreach(Tenant::all() as $ten){
            if($ten->id != 'admin'){
                DB::setDefaultConnection('tenant');
                \Config::set('database.connections.tenant.database', 'tenant'.$ten->id);
                DB::reconnect('tenant');
                $res = Reseller::find(1);

                $collection->push((object)['reseller_id' => $res->reseller_id,
                                           'reseller_name'=>$res->reseller_name

                ]);
            }

        }

        return $collection;
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
                        Select::make('reseller') // customfield_shorttext40
                            ->label('Reseller')
                            ->default('Buroserv')
                            ->options($this->resellers->pluck('reseller_name', 'reseller_id'))
                            ->searchable()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {
                                $this->resellerId = $state ?? null;
                                $resellerModel = Reseller::on('tenant')->where('reseller_id', $this->resellerId)->first();

                                $set('resellerId', $this->resellerId);
                                $set('reseller', $resellerModel->reseller_name);
                            })
                            ->columnSpan(6),
                        Hidden::make('resellerId')
                            ->default(2), // customfield_shorttext37
                        Hidden::make('reseller')
                            ->disabled(),

                        TextInput::make('customfield_shorttext7') // Reseller Phone number
                            ->default('1300726210')
                            ->telRegex('((0|[+]61)[2,3,4,7,8]\d{8})|((1300|1800)\d{6})|((13)\d{4})|((\+)\d*)')
                            ->maxLength(12)
                            ->label('Reseller Phone')
                            ->helperText('Landline area code (02, 03 etc) or mobile (04). No spaces.')
                            ->columnSpan(3),
                        TextInput::make('customfield_shorttext25')->email() // Reseller Email address (required)
                            ->required()
                            ->default('provisioning@buroserv.com.au')
                            ->label('Reseller Email')
                            ->maxLength(50)
                            ->columnSpan(3),

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
                            ->default('31914000000046147') // Set as Error 31914000000046151 for testing. Should be 31914000000046147 Order Submitted.
                            ->disabled(),
                        Hidden::make('customfield_shorttext20')
                            ->default(
                                data_get($this->sq->raw, 'addressDetails.unitNumber')
                            )
                            ->label('unitNumber')
                            ->disabled(),
                        Hidden::make('customfield_shorttext23')
                            ->default(
                                data_get($this->sq->raw, 'addressDetails.streetNumber')
                            )
                            ->label('streetNumber')
                            ->disabled(),
                        Hidden::make('customfield_shorttext9')
                            ->default(
                                data_get($this->sq->raw, 'addressDetails.street')
                            )
                            ->label('street')
                            ->disabled(),
                        Hidden::make('customfield_shorttext8')
                            ->default(
                                data_get($this->sq->raw, 'addressDetails.suburb')
                            )
                            ->label('suburb')
                            ->disabled(),
                        Hidden::make('customfield_shorttext13')
                            ->default(
                                data_get($this->sq->raw, 'addressDetails.state')
                            )
                            ->label('state')
                            ->disabled(),
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
                            ->reactive()
                            ->placeholder("Select infrastructure")
                            ->columnSpan(4),
                        ViewField::make('portId')
                            ->label('portId')
                            ->view('superloop.orders.components.port-select')
                            ->columnSpan(12),

                        // Add Toggle
                        Toggle::make('show_radius')
                            ->label('Add Radius Details')
                            ->reactive()
                            ->requiredWith(['rad_user', 'ims_user', 'rad_pass', 'rad_ip', 'radiusIP_id', 'customfield_dropdown3'])
                            ->afterStateUpdated(
                                fn ($state, callable $set) => [
                                    $set('rad_user', $state ? $this->buroflowReference : null),
                                    $set('ims_user', $state ? null : $this->ims_user),
                                    $set('customfield_dropdown3', $state ? '31914000000046146': null),
                                    $set('rad_pass', $state ? $this->radius_password : null),
                                    $set('rad_ip', $state ? $this->radiusIP()->ip_address : null),
                                    $set('radiusIP_id', $state ? $this->radiusIP()->id : null)
                                ]
                                )
                            ->columnSpan(12),

                        // Substitute IMS User Details
                        TextInput::make('ims_user')
                            ->label('IMS Username')
                            ->requiredWith('show_radius')
                            ->hidden(
                                fn (\Filament\Forms\Get $get): bool => $get('show_radius') != false
                            )
                            ->columnSpan(4),

                        // Radius User Details
                        TextInput::make('rad_user')
                            ->label('Radius Username')
                            ->requiredWith('show_radius')
                            ->hidden(
                                fn (\Filament\Forms\Get $get): bool => $get('show_radius') == false
                            )
                            ->columnSpan(3),

                        // Realms
                        Select::make('realm')
                            ->label('Realm')
                            ->default('@nbn.buroserv.com.au')
                            ->requiredWith('show_radius')
                            ->options(realm::on('mysql')->pluck('realm_name', 'realm_name'))
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
                            ->columnSpan(3)
                            ->hidden(
                                fn (\Filament\Forms\Get $get): bool => $get('show_radius') == false
                            ),
                        Hidden::make('realmId')
                            ->default(57),

                        // Radius Password
                        TextInput::make('rad_pass')
                            ->label('Radius Password')
                            ->requiredWith('show_radius')
                            ->hidden(
                                fn (\Filament\Forms\Get $get): bool => $get('show_radius') == false
                            )
                            ->columnSpan(2),
                        // Radius IP Address
                        TextInput::make('rad_ip')
                            ->label('Radius IP')
                            ->requiredWith('show_radius')
                            ->hidden(
                                fn (\Filament\Forms\Get $get): bool => $get('show_radius') == false
                            )
                            ->disabled()
                            ->columnSpan(2),
                        // Radius I P S Table ID
                        Hidden::make('radiusIP_id'),

                        // Set Order to External with Radius details
                        Hidden::make('customfield_dropdown3')
                        // ->default('31914000000046145') // default set to internal
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
                            ->label('NBN Speeds')
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
                                '31914000000046194' => '48 Months',
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
