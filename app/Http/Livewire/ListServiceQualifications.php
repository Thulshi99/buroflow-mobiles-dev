<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Arr;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TagsColumn;
use Filament\Tables\Columns\BadgeColumn;
use Illuminate\Database\Eloquent\Builder;
use App\Models\ServiceQualification as SQ;
use App\Models\ServiceQualification;
use Filament\Tables\Actions\Action;
use Filament\Tables\Concerns\InteractsWithTable;
use Illuminate\Database\Eloquent\Relations\Relation;

class ListServiceQualifications extends Component implements HasTable
{
    use InteractsWithTable;

    protected $queryString = [
        'tableFilters',
        'tableSortColumn',
        'tableSortDirection',
        'tableSearchQuery' => ['except' => ''],
    ];

    protected function getTableActions(): array
    {
        return [
            Action::make('Create Order')
                ->url(
                    fn (ServiceQualification $record): string => route(
                        'nbn.order.create',
                        ['locId' => $record->locId]
                    )
                )
                ->color('success')
                ->icon('heroicon-s-plus')
                ->openUrlInNewTab()
        ];
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('loc_id')->label('Loc ID')
                ->extraAttributes(['class' => 'p-px text-sm'])
                ->default('N/A')
                ->sortable()
                ->searchable(),
            TextColumn::make('tenant_id')->label('Created By')
                ->extraAttributes(['class' => 'p-px text-sm'])
                ->visible($this->userIsAdmin())
                ->searchable()
                ->sortable()
                ->default('N/A'),
            TextColumn::make('type')->label('Type')
                ->extraAttributes(['class' => 'p-px text-sm'])
                ->default('N/A'),
            BadgeColumn::make('technologyType')->label('Tech')
                ->extraAttributes(['class' => 'p-px text-sm'])
                ->colors([
                    'primary' => fn ($tech): bool => in_array($tech, ['FTTB', 'FTTN', 'FTTC']),
                    'text-sky-700 bg-sky-500/10' => fn ($tech): bool => $tech === 'HFC',
                    'success' => fn ($tech): bool => $tech === 'FTTP',
                    'danger' => fn ($tech): bool => $tech === 'SAT',
                    'text-teal-700 bg-teal-500/10' => fn ($tech): bool => $tech === 'FW',
                ])
                ->default('N/A'),
            TextColumn::make('sourceType')->label('Source Type')
                ->extraAttributes(['class' => 'p-px text-sm'])
                ->toggleable(true, true)
                ->default('N/A'),
            TextColumn::make('address')->label('Address')
                ->extraAttributes(['class' => 'p-px text-sm'])
                ->limit(47, '...')
                ->tooltip(function (TextColumn $column): ?string {
                    $state = $column->getState();
                    if (strlen($state) <= $column->getLimit()) {
                        return null;
                    }
                    // Only render the tooltip if the column contents exceeds the length limit.
                    return $state;
                })
                ->default('N/A'),
            TextColumn::make('status')->label('Status')
                ->extraAttributes(['class' => 'p-px text-sm'])
                ->default('N/A'),
            TagsColumn::make('details')->label('Product Speeds Available')
                ->getStateUsing(fn ($record): array => $this->getSpeeds($record))
                ->separator(',')
                ->toggleable(true, true)
                ->extraAttributes(['class' => 'p-px text-sm'])
                ->default('N/A'),
            BadgeColumn::make('altTech')
                ->label('Fibre?')
                ->default('N/A')
                ->icons([
                    'heroicon-o-x-mark',
                    'heroicon-o-check' => fn ($tech): bool => $tech === 'Fibre'
                ])
                ->colors([
                    'danger',
                    'success' => fn ($tech): bool => $tech === 'Fibre'
                ])
                ->extraAttributes(['class' => 'p-px text-sm flex align-center']),
            TextColumn::make('created_at')
                ->extraAttributes(['class' => 'p-px text-sm'])
                // ->dateTime('D jS M H:i:s')
                ->since()
                ->sortable(),
        ];
    }


    protected function getTableQuery(): Builder|Relation
    {
        if (tenant()) {
            $this->tenant_id = tenant()->id;
        }

        $tenant = $this->tenant_id;
        tenancy()->initialize($tenant);
        
        $sq = SQ::Query()->orderBy('created_at', 'DESC');

        return $sq;
    }

    public function render()
    {
        return view('livewire.list-table');
    }

    private function requestIsCentralDomain()
    {
        return in_array(request()->getHost(), config('tenancy.central_domains'));
    }

    private function getSpeeds($record): array
    {
        $options = Arr::flatten(data_get($record->raw, 'availableProducts.*.options'));
        foreach ($options as $i => $label) {
            $label = str($label)->replace('Home ', '');
            $label = str($label)->replace('fast', '');
            $label = str($label)->replace('Fixed Wireless', 'FW');
            $options[$i] = str($label)->replace('Fast', '');
        }

        return $options;
    }

    private function userIsAdmin()
    {
        if (tenant()) {
            return tenant()->id == 'admin';
        }

        if ($this->requestIsCentralDomain()) {
            return request()->user()->currentTeam->tenant_id == 'Admin';
        }

        return false;
    }
}
