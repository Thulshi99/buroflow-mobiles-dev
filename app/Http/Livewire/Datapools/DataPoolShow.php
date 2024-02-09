<?php

namespace App\Http\Livewire\Datapools;
use Illuminate\Http\Request;
use App\Models\Tenant;
use App\Services\TenantService;
use Filament\Forms;
use Filament\Pages\Actions\Action;
use Filament\Forms\Components\Grid;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Livewire\Component;
use App\Models\DataPool;
use Illuminate\Support\Facades\Redirect;
use Filament\Notifications\Notification;
use Throwable;

class DataPoolShow extends Component implements HasForms
{
    use InteractsWithForms;

    public $customer;
    public $carrier;
    public $pool_name;
    public $department;
    public $rate_plan;
    public $email_address_1;
    public $email_address_2;
    public $email_address_3;
    public $tenant_id;

    public $datapool_id;

    public $datapool;

    public function render()
    {
        return view('livewire.datapools.data-pool-show');
    }

    public function mount($id): void
    {
        if (tenant()) {
            $this->tenant_id = tenant()->id;
        }

        // Use the $id parameter to perform actions or retrieve data pool
        $datapool = DataPool::find($id);
        $this->datapool = $datapool;

        $this->pool_name =$datapool->description;
        $this->customer = $datapool->customer_name;
        $this->department = $datapool->department;
        $this->rate_plan = $datapool->data_plan_id;
        $this->email_address_1 =$datapool->email_address_1;
        $this->email_address_2 =$datapool->email_address_2;
        $this->email_address_3 =$datapool->email_address_3;
        $this->carrier = $datapool->carrier;


        if($datapool->datapool_id == 0)
        {
            $this->datapool_id = "N/A";
        }
        else
        {
            $this->datapool_id = $datapool->datapool_id;
        }
    }


    protected function getFormSchema(): array
    {
        return [
            Grid::make()
                ->schema([
                    Forms\Components\TextInput::make('datapool_id')
                    ->label("Data Pool ID")
                    ->required()
                    ->disabled()
                    ->columnSpan(1),
                    Forms\Components\TextInput::make('customer')
                    ->label("Reseller")
                    ->required()
                    ->hidden()
                    ->columnSpan(1),
                    Forms\Components\Select::make('carrier')
                    ->label("Carrier")
                    ->required()
                    ->disabled()
                    ->hidden()
                    ->options([
                        1 => 'Telstra WME'
                    ]),
                    Forms\Components\TextInput::make('pool_name')
                    ->label("Pool Name")
                    ->required()
                    ->disabled()
                    ->columnSpan(1),
                    Forms\Components\TextInput::make('department')
                        ->label('Department')
                        ->disabled()
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
                        ->columnSpan(1),
                    Forms\Components\TextInput::make('email_address_2')
                        ->label('Email #2')
                        ->required()
                        ->columnSpan(1),
                    Forms\Components\TextInput::make('email_address_3')
                        ->label('Email #3')
                        ->required()
                        ->columnSpan(1),
                    ])
        ];
    }

    public function update()
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

            if($this->tenant_id != 'admin' && $state)
            {
                //duplicate data to Admin
                tenancy()->initialize('admin');
                $this->datapool->update([
                    "description" => $pool_name,
                    "customer_name" => $customer,
                    "department" => $department,
                    "carrier" => $carrier,
                    "email_address_1" => $email_address_1,
                    "email_address_2" => $email_address_2,
                    "email_address_3" => $email_address_3,
                    "reseller_id" => 1,
                    "data_plan_id" => $data_plan_id,
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

                $tenant->run(function () use ($pool_name,$data_plan_id, $email_address_1,$email_address_2,$email_address_3, $current_team_id,$customer,
                                                $department,$carrier) {
                    $this->datapool->update([
                        "description" => $pool_name,
                        "customer_name" => $customer,
                        "department" => $department,
                        "carrier" => $carrier,
                        "email_address_1" => $email_address_1,
                        "email_address_2" => $email_address_2,
                        "email_address_3" => $email_address_3,
                        "reseller_id" => 1,
                        "data_plan_id" => $data_plan_id,
                    ]);
                });

                session()->flash('message', 'Data Pool Details Updated Successfully');
                // Notification::make()
                // ->title('Saved successfully')
                // ->success()
                // ->send();

                return redirect()->back();
            }
        // }
        // catch(Throwable $e)
        // {
        //     session()->flash('message', 'An Error Occurred While Updating Datapool Details');
        // }

    }
}
