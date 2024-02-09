<?php

namespace App\Http\Livewire;

use App\Models\Carrier;
use Filament\Tables;
use Filament\Tables\Actions\IconButtonAction;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;

class ListCarriers extends Component implements Tables\Contracts\HasTable
{

    use Tables\Concerns\InteractsWithTable;

    protected $queryString = [
        'tableFilters',
        'tableSortColumn',
        'tableSortDirection',
        'tableSearchQuery' => ['except' => ''],
    ];

    protected function getTableQuery(): Builder
    {
        return Carrier::query();
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('name')->label('Name')
                ->extraAttributes(['class' => 'p-px text-sm'])
                ->sortable()->searchable(),
            TextColumn::make('created_at')->label('Created')
                ->extraAttributes(['class' => 'p-px text-sm'])
                ->dateTime('D jS M H:i:s')->sortable()->searchable(),
            TextColumn::make('updated_at')->label('Updated')
                ->extraAttributes(['class' => 'p-px text-sm'])
                ->dateTime('D jS M H:i:s')->sortable()->searchable(),
            BooleanColumn::make('is_active')->label('Active?')
                ->extraAttributes(['class' => 'p-px text-sm'])
                ->trueColor('success')
                ->falseColor('danger'),
            // TextColumn::make('deleted_at')->label('Deleted')
            //     ->extraAttributes(['class' => 'p-px text-sm'])
            //     ->dateTime('D jS M H:i:s')->sortable()->searchable(),
        ];
    }

    protected function getTableFilters(): array
    {
        return [
            SelectFilter::make('is_active')->label("Carrier active?")
                ->options([
                    true => 'Active',
                    false => 'Inactive',
                ]),
        ];
    }
    
    protected function getTableActions(): array
    {
        return [
            IconButtonAction::make('edit')
                ->label('Edit Carrier')
                ->url(fn (Carrier $record): string => route('carriers.edit', $record))
                ->color('warning')
                ->icon('heroicon-o-pencil'),
            IconButtonAction::make('delete')
                ->label('Delete Carrier')
                ->url(fn (Carrier $record): string => route('carriers.destroy', $record))
                ->color('danger')
                ->icon('heroicon-o-trash'),
        ];
    }

    public function render()
    {
        return view('livewire.list-table');
    }
    
}
