<?php

namespace App\Http\Livewire;

use Filament\Tables;
use Livewire\Component;
use App\Models\Carrier;
use App\Models\SimCard;
use Illuminate\Contracts\View\View;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\MultiSelectFilter;

class ListSimcards extends Component implements Tables\Contracts\HasTable
{
    use Tables\Concerns\InteractsWithTable;

    private $simStatusArray = [
        SimCard::STATUS_AVAILABLE => 'Available',
        SimCard::STATUS_ALLOCATED => 'Allocated',
        SimCard::STATUS_LOCKED => 'Locked',
        SimCard::STATUS_LOST_STOLEN => 'Lost/Stolen',
        SimCard::STATUS_PENDING => 'Pending',
        SimCard::STATUS_RESERVED => 'Reserved',
        SimCard::STATUS_TERMINATED => 'Terminated',
    ];

    protected $queryString = [
        'tableFilters',
        'tableSortColumn',
        'tableSortDirection',
        'tableSearchQuery' => ['except' => ''],
    ];

    protected function getTableQuery(): Builder
    {
        // handle separate query based on domain, need trait or middleware approach instead
        if(tenant()) {
            return SimCard::query()->whereTenantId(tenant()->id);
        }

        if (!$this->requestIsCentralDomain()) {
            $tenant_id = request()->user()->currentTeam->tenant_id;
            return SimCard::query()->whereTenantId($tenant_id);
        }
        
        return SimCard::query();
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('order_id')->label('Order ID')
                ->extraAttributes(['class' => 'p-px text-sm'])
                ->default('N/A')->sortable(),
            TextColumn::make('tenant_id')->label('Reseller ID')
                ->extraAttributes(['class' => 'p-px text-sm'])
                ->default('N/A')->sortable()->searchable(),
            TextColumn::make('imei')->label('IMEI')
                ->extraAttributes(['class' => 'p-px text-sm'])
                ->sortable()->searchable(),
            TextColumn::make('carrier.name')->label('Carrier')
                ->url(fn (SimCard $record): string => route('carriers.show', ['carrier' => $record->carrier_id]))
                ->extraAttributes(['class' => 'p-px text-sm'])
                ->default('N/A')->sortable(),
            TextColumn::make('service_id')->label('Service ID')
                ->extraAttributes(['class' => 'p-px text-sm'])
                ->sortable()->searchable(),
            TextColumn::make('imsi')->label('IMSI')
                ->extraAttributes(['class' => 'p-px text-sm'])
                ->sortable(),
            TextColumn::make('iccid')->label('ICCID')
                ->extraAttributes(['class' => 'p-px text-sm'])
                ->sortable(),
            BadgeColumn::make('status')
                ->extraAttributes(['class' => 'p-px text-sm'])
                ->sortable()->searchable()
                ->enum($this->simStatusArray)
                ->colors([
                    'primary',
                    'success' => SimCard::STATUS_AVAILABLE,
                    'danger' => fn ($state): bool => in_array($state, [
                        SimCard::STATUS_TERMINATED,
                        SimCard::STATUS_LOST_STOLEN,
                    ]),
                    'warning' => fn ($state): bool => in_array($state, [
                        SimCard::STATUS_LOCKED,
                        SimCard::STATUS_RESERVED,
                    ]),

                ]),
            TextColumn::make('created_at')
                ->extraAttributes(['class' => 'p-px text-sm'])
                ->dateTime('D jS M H:i:s')->sortable()->searchable(),
            BooleanColumn::make('exists_in_iboss')->label('iBoss?')
                ->extraAttributes(['class' => 'p-px text-sm flex justify-center'])
                ->trueColor('success')
                ->falseColor('danger'),
        ];
    }

    protected function getTableFilters(): array
    {
        return [
            MultiSelectFilter::make('carrier')->relationship('carrier', 'name'),
            MultiSelectFilter::make('status')
                ->options($this->simStatusArray),
            SelectFilter::make('exists_in_iboss')
                ->options([
                    true => "True",
                    false => "False",
                ]),
        ];
    }

    public function render()
    {
        return view('livewire.list-table');
    }

    private function requestIsCentralDomain()
    {
        return in_array(request()->getHost(), config('tenancy.central_domains'));
    }
}
