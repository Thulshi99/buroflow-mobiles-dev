<?php

namespace App\Http\Livewire\Datapools;

use Filament\Forms\Contracts\HasForms;
use Livewire\Component;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use App\Models\ServiceQualification;
use Filament\Forms\Concerns\InteractsWithForms;
use App\Models\DataPool;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Wizard;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use App\Http\Requests\CreateDatapoolRequest;
use Artisaninweb\SoapWrapper\SoapWrapper;
use throwable;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action;


class DataPoolCreateForm extends Component implements HasForms
{
    use InteractsWithForms;

    public $customer= 'John Green';
    public $carrier = 'Telstra WME';
    public $pool_name;
    public $department='Buroserv';
    public $rate_plan;
    public $email_address_1;
    public $email_address_2;
    public $email_address_3;
    public $tenant_id;
    public $note_id;

    protected $soapWrapper;

    public function render()
    {
        return view('livewire.datapools.data-pool-create-form');
    }

    public function mount(): void
    {
        if (tenant()) {
            $this->tenant_id = tenant()->id;
        }
    }

    protected function getFormSchema(): array
    {
        return [
            Grid::make()
                ->schema([
                    // Forms\Components\TextInput::make('datapool_id')
                    // ->label("Data Pool ID")
                    // ->required()
                    // ->disabled()
                    // ->columnSpan(1),
                    Forms\Components\TextInput::make('customer')
                    ->label("Reseller")
                    ->required()
                    ->hidden()
                    ->columnSpan(1),
                    Forms\Components\Select::make('carrier')
                    ->label("Carrier")
                    ->required()
                    ->autofocus()
                    ->hidden()
                    ->disabled()
                    ->options([
                        1 => 'Telstra WME'
                    ]),
                    Forms\Components\TextInput::make('pool_name')
                    ->label("Pool Name")
                    ->required()
                    ->autofocus()
                    ->columnSpan(1),
                    Forms\Components\TextInput::make('department')
                        ->label('Department')
                        ->disabled()
                        ->autofocus()
                        ->hidden()
                        ->columnSpan(1),
                    Forms\Components\Select::make('rate_plan')
                        ->label('Rate Plan')
                        ->required()
                        ->options([
                            23045 => 'BURO POOL MANUAL TOP UP – 11139460',
                            23046 => 'BURO POOL AUTO TOP UP – 11139459',
                        ]),
                        Forms\Components\TextInput::make('email_address_1')
                        ->label('Email #1')
                        ->required()
                        ->autofocus()
                        ->columnSpan(1),
                        Forms\Components\TextInput::make('email_address_2')
                        ->label('Email #2')
                        ->autofocus()
                        ->columnSpan(1),
                        Forms\Components\TextInput::make('email_address_3')
                        ->label('Email #3')
                        ->autofocus()
                        ->columnSpan(1),
                    ])
        ];
    }

    public function submit()
    {
        // try
        // {
            $state = $this->form->getState();
            $pool_name = $state['pool_name'];
            $customer = $this->customer;
            $carrier =  $this->carrier;
            $department = $this->department;
            $data_plan_id = $state['rate_plan'];
            $email_address_1 = $state['email_address_1'];
            $email_address_2 = $state['email_address_2'];
            $email_address_3 = $state['email_address_3'];

            // SOAP API Request
            $data = [
                'username' => env('OCTANE_USERNAME'),
                'password' => env('OCTANE_PASSWORD'),
                'cust_no' => env('OCTANE_DEFAULT_CUSTNO','382422'),
                'department' => $department,
                'data_plan_id' => $data_plan_id,
                'email' => $email_address_1,
                'pool_name' => $pool_name
            ];

            $request = new CreateDatapoolRequest($data);

            $endpoint = env('OCTANE_BASE_URL','https://benzine.telcoinabox.com/tiab')."/UtbOrder?wsdl";

            $this->soapWrapper = new SoapWrapper();

            $this->soapWrapper->add('UtbOrderPortBinding', function ($order) use ($endpoint){
                $order
                    ->wsdl($endpoint) // The WSDL endpoint
                    ->trace(true);  // Optional: (parameter: true/false)
            });

            $response = $this->soapWrapper->call('UtbOrderPortBinding.orderCreate', [
                new \SoapVar($request->getXmlBody(), XSD_ANYXML)
            ]);

           if ($response != null)
            {
                //Get OrderID and Error Code
                $errorCode = $response->return->errorCode;

                if($errorCode == 0)
                {
                    $order_id   = $response->return->orderId;

                    $note_id = Str::uuid()->toString();

                    if($this->tenant_id != 'admin')
                    {
                        //duplicate data to Admin
                        tenancy()->initialize('admin');
                        DataPool::create([
                            "description" => $pool_name,
                            "customer_name" => $customer,
                            "department" => $department,
                            "carrier" => $carrier,
                            "email_address_1" => $email_address_1,
                            "email_address_2" => $email_address_2,
                            "email_address_3" => $email_address_3,
                            "reseller_id" => 1,
                            "data_plan_id" => $data_plan_id,
                            "order_id" => $order_id,
                            "note_id" => $note_id
                        ]);
                        tenancy()->end();
                    }

                    if($this->tenant_id == 'admin')
                    {
                        $current_team_id = 1;
                    }
                    else{
                        $current_team_id = 0;
                    }

                    if ($state) {
                        $tenant_id = $this->tenant_id;
                        $tenant = Tenant::find($tenant_id);

                        $tenant->run(function () use ($pool_name,$data_plan_id, $email_address_1, $email_address_2, $email_address_3, $current_team_id,$customer,
                                                        $department,$carrier,$note_id, $order_id) {

                            DataPool::create([
                                "description" => $pool_name,
                                "customer_name" => $customer,
                                "department" => $department,
                                "carrier" => $carrier,
                                "email_address_1" => $email_address_1,
                                "email_address_2" => $email_address_2,
                                "email_address_3" => $email_address_3,
                                "reseller_id" => 1,
                                "data_plan_id" => $data_plan_id,
                                "order_id" => $order_id,
                                "note_id" => $note_id
                            ]);
                        });

                        Notification::make()
                        ->title('Data Pool Creation Request Has Been Sent Successfully')
                        ->success()
                        ->body('It will take around 15 minutes to completely create the data pool.')
                        ->actions([
                            Action::make('Ok')
                                ->button()
                                ->url(route('datapools.index'), shouldOpenInNewTab: false)
                            ])
                        ->persistent()
                        ->send();
                    }
                    else
                    {
                        Notification::make()
                        ->title('Error Ocurred : Data Has Not Been Collected Properly')
                        ->danger()
                        ->send();
                    }

                }
                else
                {
                    Notification::make()
                    ->title('Error Ocurred : There Is An Error In API Call')
                    ->danger()
                    ->send();
                }
            }
            else
            {
                Notification::make()
                ->title('Error Ocurred : API Call Response Is Null')
                ->danger()
                ->send();
            }
        // }
        // catch (Throwable $e)
        // {
        //     Notification::make()
        //     ->title('Error Ocurred While Creating the Data Pool')
        //     ->danger()
        //     ->send();
        // }

    }
}

