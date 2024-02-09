<?php

namespace App\Http\Livewire\Datapools;

use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Livewire\Component;
use App\Models\DataPool;
use App\Models\DatapoolTopupOption;
use App\Models\Tenant;
use Illuminate\Support\Facades\Redirect;

class AddDataTopup extends Component implements HasForms
{
    use InteractsWithForms;

    public $tenant_id;

    public $datapool_id;

    public function mount($datapool_id): void
    {
        if (tenant()) {
            $this->tenant_id = tenant()->id;
        }

        // Use the $datapool_id parameter to perform actions or retrieve data pool
        $this->datapool_id = $datapool_id;
    }

    public function render()
    {
        return view('livewire.datapools.add-data-topup');
    }

    protected function getFormSchema(): array
    {
        return [
            Grid::make()
                ->schema([
                    Forms\Components\Select::make('topup_option')
                    ->label("Top-Up Option")
                    ->required()
                    ->autofocus()
                    ->options([
                        1 => '50 GB',
                        2 => '100 GB',
                        3 => '150 GB',
                        4 => '250 GB',
                        5 => '500 GB',
                        6 => '1000 GB'
                    ])
                ])
        ];
    }
}
