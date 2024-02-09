<?php

namespace App\Http\Livewire\Orders;

use App\Models\Order;
use App\Models\User;
use Filament\Tables;
use Filament\Tables\Actions\LinkAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use DB;
use Closure;
use App\Models\Tenant;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Forms\Components;
use Filament\Forms;

class ListOrders extends Component implements Tables\Contracts\HasTable
{

    use Tables\Concerns\InteractsWithTable;

    protected $queryString = [
        'tableFilters',
        'tableSortColumn',
        'tableSortDirection',
        'tableSearchQuery' => ['except' => ''],
    ];

    public $order_status;
    public $orders;
    public $show_porting=false;

    public function mount($order_status)
    {
        $this->order_status = $order_status;
        if($this->order_status == 'new-order'){
            $this->show_porting=true;
        }
        // $this->tableOrderShow($order_status);

    }

    protected function getTableQuery(): Builder
    {
        $orders = Order::where('order_status',$this->order_status)->with('customer')->with('reseller')->orderBy('id', 'desc');
        return $orders;
    }

    protected function getTableColumns(): array
    {
        return [

            BadgeColumn::make('order_status')
                ->label('Status')
                ->color(fn (string $state): string => match ($state) {
                    'rejected' => 'danger',
                    'completed' => 'success',
                    'order-lodged' => 'info',
                    'cancelled' => 'danger',
                    'pending' => 'warning',
                    'new-order' => 'primary',
                })
                ->formatStateUsing(function ($record) {
                    return $record->order_status === 'completed' ? 'active' : 'diactive';
                })
                ->hidden($this->order_status !== "completed")
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
                ->getStateUsing( function (Order $record){
                    if($record->vendor_id == 1){
                        return '60000500 Aussiecom';
                    }
                    return '60000500 Aussiecom';

                 })
                 ->searchable(isIndividual:true, isGlobal:true),
            TextColumn::make('vendor_account_id')->label('Site')
                ->extraAttributes(['class' => 'p-px text-sm'])
                ->sortable()
                ->getStateUsing( function (Order $record){
                    if($record->vendor_account_id == 'default'){
                        return 'DEFAULT';
                    }
                    return $record->vendor_account_id;

                 })
                 ->searchable(isIndividual:true, isGlobal:true),
            // BadgeColumn::make('order_status')
            //     ->label('Status')
            //     ->color(fn (string $state): string => match ($state) {
            //         'rejected' => 'danger',
            //         'completed' => 'success',
            //         'order-lodged' => 'info',
            //         'cancelled' => 'danger',
            //         'pending' => 'warning',
            //         'new-order' => 'primary',
            //     })
            //     ->searchable(isIndividual:true, isGlobal:false),
            TextColumn::make('created_at')->label('Order Date')
            ->extraAttributes(['class' => 'p-px text-sm'])
            ->dateTime('Y-m-d h:m:s')
            ->wrap()
            ->searchable(isIndividual:true, isGlobal:true),
            TextColumn::make('reseller.reseller_name')->label('Ordered By')
                ->extraAttributes(['class' => 'p-px text-sm'])
                ->sortable()
                ->searchable(isIndividual:true, isGlobal:true),
            TextColumn::make('porting')->label('Porting')
                ->extraAttributes(['class' => 'p-px text-sm'])
                ->sortable()
                ->searchable(isIndividual:true, isGlobal:true)
                ->hidden($this->order_status !== "new-order"),
            TextColumn::make('simcard')->label('SIM Card #')
                ->extraAttributes(['class' => 'p-px text-sm'])
                ->sortable()
                ->searchable(isIndividual:true, isGlobal:true)
                ->hidden($this->order_status !== "pending" && $this->order_status !== "order-lodged" && $this->order_status !== "rejected" && $this->order_status !== "completed"),
            TextColumn::make('tracking')->label('Tracking #')
                ->extraAttributes(['class' => 'p-px text-sm'])
                ->sortable()
                ->hidden($this->order_status !== "pending" && $this->order_status !== "order-lodged" && $this->order_status !== "rejected")
                ->searchable(isIndividual:true, isGlobal:true),
            TextColumn::make('mobile_number')->label('Mobile #')
                ->extraAttributes(['class' => 'p-px text-sm'])
                ->sortable()
                ->searchable(isIndividual:true, isGlobal:true),
            TextColumn::make('plan')->label('Plan')
                ->extraAttributes(['class' => 'p-px text-sm'])
                ->sortable()
                ->searchable(isIndividual:true, isGlobal:true)
                ->hidden($this->order_status !== "completed"),
            TextColumn::make('customer.customercontactinfos.display_name')->label('End User')
                ->extraAttributes(['class' => 'p-px text-sm'])
                ->sortable()
                ->wrap()
                ->searchable(isIndividual:true, isGlobal:true),
        ];
    }

    // protected function getTableFilters(): array
    // {
    //     return [
    //         SelectFilter::make('order_status')
    //         ->options([
    //             'new-order' => 'New order',
    //             'pending' => 'Pending Confirmation',
    //             'order-lodged' => 'Order Lodged',
    //             'rejected' => 'Rejected',
    //             'cancelled' => 'Cancelled',
    //             'completed' => 'Completed',
    //             ]),
    //     ];
    // }

    // public function getTableRecordKey(Customer $record): string
    // {
    //     return '';
    // }

    public function tableOrderShow($status){
        switch ($status) {
            case 'new-order':
                    $this->show_porting = true;
                break;
            case 'order-lodged':
                // Code to execute if $variable equals value2
                break;
            // You can add more cases as needed
            case 'pending':

                break;
            default:
                // Code to execute if $variable doesn't match any case
                $this->show_porting = true;
        }
    }


    protected function getTableActions(): array
    {

        if($this->order_status == "new-order" || $this->order_status == "order-lodged" || $this->order_status == "pending"){
            return [
                // Action::make('order details')
                //     ->iconButton()
                //     ->icon('heroicon-s-plus-circle')
                //     ->url(fn (Order $record): string => route('orders.show', $record)),
                // Tables\Actions\ActionGroup::make([
                //     // Tables\Actions\ViewAction::make(),
                //     Action::make('Order Details')
                //         ->iconButton()
                //         ->icon('gmdi-view-agenda-o')
                //         ->url(fn ($record) => "javascript:openViewRecordModal({$record->id})"),
                //         // ->url(fn (Order $record): string => route('orders.show', $record)),

                //     Tables\Actions\Action::make('Update Details')
                //     ->color('primary')
                //     ->icon('gmdi-edit')
                //     ->url(fn (Order $record): string => route('orders.edit', $record)),
                // ]),
            ];
        }else{
            return [];
        }

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
