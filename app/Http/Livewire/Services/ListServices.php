<?php

namespace App\Http\Livewire\Services;

use App\Models\MobileService;
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
use Filament\Tables\Columns\BadgeColumn;

class ListServices extends Component implements Tables\Contracts\HasTable
{

    use Tables\Concerns\InteractsWithTable;

    protected $queryString = [
        'tableFilters',
        'tableSortColumn',
        'tableSortDirection',
        'tableSearchQuery' => ['except' => ''],
    ];

    public $services;

    protected function getTableQuery(): Builder
    {
        $services = MobileService::orderBy('id', 'desc');
        return $services;
    }

    // public function getTableRecordKey(Customer $record): string
    // {
    //     return uniqid();
    // }

    protected function getTableColumns(): array
    {
        return [
            BadgeColumn::make('service_status')
                ->label('Status')
                ->color(fn (string $state): string => match ($state) {
                    'deactive' => 'danger',
                    'active' => 'success',
                })
                ->searchable(isIndividual:true, isGlobal:false),
            TextColumn::make('order_id')->label('Order ID')
                ->extraAttributes(['class' => 'p-px text-sm'])
                ->sortable()
                ->searchable(isIndividual:true, isGlobal:true),
            TextColumn::make('vendor_id')->label('Account')
                ->extraAttributes(['class' => 'p-px text-sm'])
                ->sortable()
                ->wrap()
                // ->extraAttributes([
                //     'class' => 'break-words'
                // ])
                ->getStateUsing( function (MobileService $record){
                    if($record->vendor_id == 1){
                        return '60000500 Aussiecom';
                    }
                    return '60000500 Aussiecom';

                 })
                 ->searchable(isIndividual:true, isGlobal:true),
            TextColumn::make('cost_centre')->label('Cost Centre')
                ->extraAttributes(['class' => 'p-px text-sm'])
                ->sortable()
                ->getStateUsing( function (MobileService $record){
                    if($record->cost_centre !== 'default'){
                        return $record->cost_centre;
                    }
                    return 'DEFAULT';

                 })
                 ->searchable(isIndividual:true, isGlobal:true),
            TextColumn::make('created_at')->label('Order Date')
                 ->extraAttributes(['class' => 'p-px text-sm'])
                 ->dateTime('Y-m-d h:m:s')
                 ->wrap()
                 ->searchable(isIndividual:true, isGlobal:true),
            TextColumn::make('mobile_number')->label('Mobile')
                ->extraAttributes(['class' => 'p-px text-sm'])
                ->sortable()
                ->searchable(isIndividual:true, isGlobal:true),
            TextColumn::make('customer.customercontactinfos.display_name')->label('End User')
                ->extraAttributes(['class' => 'p-px text-sm'])
                ->sortable()
                ->wrap()
                ->searchable(isIndividual:true, isGlobal:true),
            TextColumn::make('reseller.reseller_name')->label('Ordered By')
                ->extraAttributes(['class' => 'p-px text-sm'])
                ->sortable()
                ->searchable(isIndividual:true, isGlobal:true),

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
                // Action::make('order details')
                //     ->iconButton()
                //     ->icon('heroicon-s-plus-circle')
                //     ->url(fn (Order $record): string => route('orders.show', $record)),
                Tables\Actions\ActionGroup::make([
                    // Tables\Actions\ViewAction::make(),
                    Action::make('Service Details')
                        ->iconButton()
                        ->icon('gmdi-view-agenda-o')
                        ->url(fn ($record) => "javascript:openViewRecordModal({$record->id})"),
                        // ->url(fn (Order $record): string => route('orders.show', $record)),

                    Tables\Actions\Action::make('Update Details')
                    ->color('primary')
                    ->icon('gmdi-edit')
                    ->url(fn (MobileService $record): string => route('services.edit', $record)),
                ]),
            ];

    }

    public function render()
    {
        return view('livewire.services.list-table');
    }

}
