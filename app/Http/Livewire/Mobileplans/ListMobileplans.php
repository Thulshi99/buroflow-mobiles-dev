<?php

namespace App\Http\Livewire\Mobileplans;

use App\Models\VendorProduct;
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

class ListMobileplans extends Component implements Tables\Contracts\HasTable
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
        $vendorproducts = VendorProduct::with('wholesalepackages')->with('retailpackages');
        return $vendorproducts;
    }

    // public function getTableRecordKey(Customer $record): string
    // {
    //     return uniqid();
    // }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('vendor_product_code')->label('Vendor Code')
                ->extraAttributes(['class' => 'p-px text-sm'])
                ->sortable()
                ->searchable(),
            TextColumn::make('vendor_product_name')->label('Vendor Plan Name')
                ->extraAttributes(['class' => 'p-px text-sm'])
                ->sortable()
                ->searchable(),
            IconColumn::make('prepaid')
                ->boolean(),
            TextColumn::make('package_type')->label('Package Type')
                ->extraAttributes(['class' => 'p-px text-sm'])
                ->sortable(),
                // ->getStateUsing( function (VendorProduct $record){
                //     if($record->wholesalepackages !== null){
                //         return $record->wholesalepackages;
                //     }
                //     return $record;

                //  }),
            BadgeColumn::make('status')
                ->label('Status')
                ->color(fn (string $state): string => match ($state) {
                    'active' => 'success',
                    'diactive' => 'danger'
                }),
            TextColumn::make('created_at')->label('Created')
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
                // Tables\Actions\ViewAction::make(),
                Action::make('Plan Details')
                    ->iconButton()
                    ->icon('gmdi-view-agenda-o')
                    ->url(fn ($record) => "javascript:openViewRecordModal({$record->id})"),
                    // ->url(fn (Order $record): string => route('orders.show', $record)),

                Tables\Actions\Action::make('Update Details')
                ->color('primary')
                ->icon('gmdi-edit')
                ->url(fn (VendorProduct $record): string => route('orders.edit', $record)),
            ])
        ];
    }

    public function render()
    {
        return view('livewire.customers.list-table');
    }

}
