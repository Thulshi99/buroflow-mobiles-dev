<?php

namespace App\Http\Livewire\Datapools;
use App\Models\Tenant;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Livewire\Component;
use App\Models\DataPool;

class DataLimitConfig extends Component implements HasForms
{
    use InteractsWithForms;

    public $tenant_id;

    public $datapool;
    public $dataLimit;
    public $warningLimit1;
    public $warningLimit2;
    public $warningLimit3;

    public function render()
    {
        return view('livewire.datapools.data-limit-config');
    }

    public function mount($datapool_id): void
    {
        if (tenant()) {
            $this->tenant_id = tenant()->id;
        }

        // Use the $id parameter to perform actions or retrieve data pool
        $datapool = DataPool::find($datapool_id);
        $this->datapool = $datapool;

        $this->dataLimit =$datapool->data_limit;
    }

    protected function getFormSchema(): array
    {
        return [
            Grid::make()
                ->schema([
                    Forms\Components\TextInput::make('dataLimit')
                    ->label("Pool Data Limit(MB)")
                    ->required()
                    ->columnSpan(1),
                    Forms\Components\Select::make('warningLimit1')
                        ->label('Warning Threshold 1')
                        //->required()
                        ->options([
                            25 => '25%',
                            50 => '50%',
                            75 => '75%',
                            100 => '100%',
                        ]),
                        Forms\Components\Select::make('warningLimit2')
                        ->label('Warning Threshold 2')
                        //->required()
                        ->options([
                            25 => '25%',
                            50 => '50%',
                            75 => '75%',
                            100 => '100%',
                        ]),
                        Forms\Components\Select::make('warningLimit3')
                        ->label('Warning Threshold 3')
                        //->required()
                        ->options([
                            25 => '25%',
                            50 => '50%',
                            75 => '75%',
                            100 => '100%',
                        ]),
                ])
        ];
    }

    public function save()
    {
        $state = $this->form->getState();
        $dataLimit = $state['dataLimit'];
        // $warningLimit1 = $state['warningLimit1'];
        // $warningLimit2 = $state['warningLimit2'];
        // $warningLimit3 = $state['warningLimit3'];

        if($this->tenant_id != 'admin' && $state)
        {
            //duplicate data to Admin
            tenancy()->initialize('admin');
            $this->datapool->update([
                "data_limit" => $dataLimit,
                // "warning_limit1" => $warningLimit1,
                // "warning_limit2" => $warningLimit2,
                // "warning_limit3" => $warningLimit3,
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

            $tenant->run(function () use ($dataLimit) {
                $this->datapool->update([
                    "data_limit" => $dataLimit,
                    // "warning_limit1" => $warningLimit1,
                    // "warning_limit2" => $warningLimit2,
                    // "warning_limit3" => $warningLimit3,
                ]);
            });

            return redirect()->back()->with('success', 'Data Limit Configurations Updated Successfully');;
        }
    }
}
