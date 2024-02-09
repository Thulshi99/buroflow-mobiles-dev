<?php

namespace App\Http\Livewire\Simcards;

use App\Models\SimCard;
use App\Models\User;
use Filament\Tables;
use Filament\Tables\Actions\LinkAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\MultiSelectFilter;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use DB;
use App\Models\Tenant;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Forms\Components;
use Filament\Forms;

class ListSimcards extends Component implements Tables\Contracts\HasTable
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
        $simcards = SimCard::with('reseller')->orderBy('id', 'desc');
        return $simcards;
    }


    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('puk_code')->label('PUK Code')
                ->extraAttributes(['class' => 'p-px text-sm'])
                ->sortable()
                ->searchable(),
            TextColumn::make('sim_card_code')->label('SIM Number')
                ->extraAttributes(['class' => 'p-px text-sm'])
                ->sortable()
                ->searchable(),
            TextColumn::make('shipvia_id')->label('Carrier')
                ->extraAttributes(['class' => 'p-px text-sm'])
                ->sortable()
                ->getStateUsing( function (SimCard $record){
                    if($record->shipvia_id == 1){
                        return 'Telstra';
                    }else if($record->shipvia_id == 2){
                        return 'Symbio';
                    }else{
                        return 'Burosev';
                    }
                 })
                ->searchable(),
            BadgeColumn::make('status')
                ->color(fn (string $state): string => match ($state) {
                    'unavailable' => 'warning',
                    'damaged' => 'danger',
                    'available' => 'primary',
                    'allocated' => 'success',
                })
                ->searchable(),
            TextColumn::make('mobile_number')->label('Mobile Number')
                ->extraAttributes(['class' => 'p-px text-sm'])
                ->sortable()
                ->searchable(),
            IconColumn::make('port')
                ->boolean(),
            TextColumn::make('reseller.reseller_name')->label('Reseller')
                ->extraAttributes(['class' => 'p-px text-sm'])
                ->sortable()
                ->searchable(),
            TextColumn::make('created_at')->label('Date Added')
                ->extraAttributes(['class' => 'p-px text-sm'])
                ->dateTime('D jS M H:i:s')->sortable(),

        ];
    }

    protected function getTableFilters(): array
    {
        return [
            // MultiSelectFilter::make('currentTeam')->relationship('currentTeam', 'name'),
        ];
    }

    // public function getTableRecordKey(Customer $record): string
    // {
    //     return '';
    // }

    protected function getTableActions(): array
    {
        return [
            Tables\Actions\ActionGroup::make([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('Edit')
                ->color('primary')
                ->icon('gmdi-edit')
                ->url(fn (SimCard $record): string => route('simcards.edit', $record)),
                // ->openUrlInNewTab(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('updateStatus')
                    ->action(function (SimCard $record, array $data): void {
                        $record->status = $data['status'];
                        $record->save();
                    })
                    ->form([
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'unavailable' => 'Unavilable',
                                'damaged' => 'Damaged',
                                'available' => 'Available',
                                'allocated' => 'Allocated',
                            ])
                            ->required(),
                    ])
                    ->requiresConfirmation()
                    ->color('success')
                    ->icon('heroicon-o-check')
            ]),
        ];
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('order_id'),
                TextEntry::make('order_status'),
                TextEntry::make('mobile_number'),
                // IconEntry::make('is_visible')
                //     ->label('Visibility')
                //     ->boolean(),
                TextEntry::make('updated_at')
                    ->dateTime(),
            ])
            ->columns(1)
            ->inlineLabel();
    }

    public function render()
    {
        return view('livewire.orders.list-table');
    }

}
