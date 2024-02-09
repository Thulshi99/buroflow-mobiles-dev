<?php

namespace App\Http\Livewire\Customers;

use App\Models\Customer;
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
use Filament\Forms\Components;
use Filament\Forms;

class ListCustomers extends Component implements Tables\Contracts\HasTable
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


        $user = User::find(auth()->user()->id);
        if($user->tenant_role == "admin"){
            $customers = Customer::with('customercontactinfos')->orderBy('id', 'desc');
        }else{

            $customers = Customer::where('reseller_id',1)->with('customercontactinfos')->orderBy('id', 'desc');

        }

        return $customers;
    }

    // public function getTableRecordKey(Customer $record): string
    // {
    //     return uniqid();
    // }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('customer_code')->label('Customer Code')
                ->extraAttributes(['class' => 'p-px text-sm'])
                ->sortable()
                ->searchable(),
            TextColumn::make('customercontactinfos.display_name')->label('Customer Name')
                ->extraAttributes(['class' => 'p-px text-sm'])
                ->sortable()
                ->searchable(),
            BadgeColumn::make('disable_account')
                ->label('Status')
                ->color(fn (string $state): string => match ($state) {
                    '0' => 'success',
                    '1' => 'danger',
                })
                ->enum([
                    '0' => 'Active',
                    '1' => 'Diactivate',
                ])
                ->searchable(),
            TextColumn::make('email')->label('Email')
                ->extraAttributes(['class' => 'p-px text-sm'])
                ->sortable()
                ->searchable(),
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
            // Action::make('edit')
            //     ->icon('heroicon-s-pencil')
            //     ->iconButton()
            //     ->color('primary')
            //     ->url(fn (Customer $record): string => route('customers.edit', $record)),
            // Action::make('data useage')
            //     ->iconButton()
            //     ->color('warning')
            //     ->icon('carbon-view')
            //     ->url(fn (Customer $record): string => route('customers.show', $record)),
            // Action::make('delete')
            //     ->iconButton()
            //     ->color('danger')
            //     ->icon('heroicon-o-trash')
            //     ->action(fn () => $this->record->delete())
            //     ->requiresConfirmation()
            Tables\Actions\ActionGroup::make([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('Edit')
                ->color('primary')
                ->icon('gmdi-edit')
                ->url(fn (Customer $record): string => route('customers.edit', $record)),
                // ->openUrlInNewTab(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('Enable/Disable Account')
                    ->action(function (Customer $record, array $data): void {
                        $record->disable_account = $this->checkToggleData($data['disable_account']);
                        $record->save();
                    })
                    ->form([
                        Forms\Components\Toggle::make('disable_account')->inline(true)->default(false),
                    ])
                    ->requiresConfirmation()
                    ->color('success')
                    ->icon('heroicon-o-check')
            ]),
        ];
    }

    private function checkToggleData($value){
        if($value == null){
            return 0;
        }
        return 1;
    }

    public function render()
    {
        return view('livewire.customers.list-table');
    }

}
