<?php

namespace App\Http\Livewire\Superloop;

use Filament\Forms;
use Livewire\Component;
use Filament\Forms\Components\Grid;
use App\Models\ServiceQualification;
use App\Rules\StartsWithLOC;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use App\Http\Integrations\APIHub\Requests\SuperloopLocationQualificationRequest;
use App\Models\User;
use App\Models\Tenant;


class LocationQualification extends Component implements HasForms
{

    use InteractsWithForms;

    public $locationId;
    public $tenant_id;

    public function mount(): void
    {
        if (tenant()) {
            $this->tenant_id = tenant()->id;
        }

        if ($this->requestIsCentralDomain()) {
            $this->tenant_id = request()->user()->currentTeam->tenant_id;
        }

        $this->locationId = request('locId') ?? null;
        $this->form->fill([
            'locationId' => $this->locationId,
        ]);
    }

    protected function getFormSchema(): array
    {
        return [
            Grid::make()
                ->schema([
                    Forms\Components\TextInput::make('locationId')
                        ->label("Location ID")
                        ->rules(['required', new StartsWithLOC])
                        ->helperText('Use the Location ID only. it must be an LOC number. Like: LOC000000000000')
                        ->required()
                        ->autofocus()
                        ->columnSpan(2),
                    Forms\Components\Toggle::make('NFAS')
                        ->label('Qualify service for NFAS (NBN Fibre Access Service)')
                        ->helperText('Check if fibre is available as an alternative technology via a standard SQ.')
                        ->columnSpan(1),
                    Forms\Components\Toggle::make('includeEnterpriseEthernet')
                        ->label('Include Enterprise Ethernet')
                        ->columnSpan(1),
                ])
        ];
    }

    public function submit()
    {
        $state = $this->form->getState();
        $state['sqType'] = $state['NFAS'] ? 'NFAS' : 'standard';
        $request = new SuperloopLocationQualificationRequest();
        $request->mergeData($state);
        $response = $request->send();

        if ($response->json('data')) {
            $tenant_id = $this->tenant_id;

            $tenant = Tenant::find($tenant_id); 
            $name = "test";

            $tenant->run(function () use ($tenant_id, $response) {
                $sq = new ServiceQualification();
                $sq->raw = $response->json('data');
                $sq->loc_id = data_get($response->json('data'), 'locationId');
                $sq->tenant_id = $this->tenant_id;
                // $sq->user_id = auth()->user()->id;
                $sq->save();
            });

            // Copy the record to SQ in Admin table if the user is not an Admin
            if($tenant_id != 'admin')
            {
                tenancy()->initialize('admin');
                $sq = new ServiceQualification();
                $sq->raw = $response->json('data');
                $sq->loc_id = data_get($response->json('data'), 'locationId');
                $sq->tenant_id = $this->tenant_id;
                $sq->save();
                tenancy()->end();
            }
        }
        $this->setMessage($response);
        // session()->flash('message', $response->json());
    }

    public function render()
    {
        return view('livewire.superloop.location-qualification');
    }

    private function setMessage($response)
    {
        $data = $response->json('data');

        // $data = data_get(session('message'), 'data');
        $tech['description'] = data_get($data, 'technologyTypeDescription');
        $tech['type'] = data_get($data, 'technologyType');
        $speeds = data_get($data, 'availableProducts.tc4.options.*');
        $service['class'] = data_get($data, 'serviceClassDescription');
        $service['classId'] = data_get($data, 'serviceClass');

        $message = [
            'raw' => $response->json(),
            'details' => [
                'Status' => isset($data['status']) ? ucwords($data['status']) : 'Unknown',
                'SQ Type' => "{$data['sqType']}",
                'Address' => $data['address'],
                'POI' => ucwords($data['poiName']) . " (" . ucwords($data['poiId']) . ")",
                'Technology Type' => "{$tech['description']} ({$tech['type']})",
                'Class' => "{$service['classId']} - {$service['class']}",
            ]
        ];
        $message['speeds'] = $speeds;
        $message['infrastructures'] = data_get($data, 'infrastructures');
        $message['installOptions'] = data_get($data, 'installOptions');
        $message['locationId'] = data_get($data, 'locationId');

        session()->flash('message', $message);
    }

    private function requestIsCentralDomain()
    {
        return in_array(request()->getHost(), config('tenancy.central_domains'));
    }
}
