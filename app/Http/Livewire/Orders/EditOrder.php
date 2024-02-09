<?php

namespace App\Http\Livewire\Orders;

use Livewire\Component;
use Filament\Forms\Components;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use App\Models\ServiceQualification;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use App\Http\Integrations\APIHub\Requests\SuperloopLocationQualificationRequest;
use App\Models\Tenant;
use App\Models\Order;
use App\Models\Customer;
use App\Models\MobilePlans;
use Redirect;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Section;
use App\Http\Controllers\OrderController;
use Illuminate\Support\HtmlString;

class EditOrder extends Component implements HasForms
{
    use InteractsWithForms;

    public $account_name;
    public $vendor_account_id;
    public $retail_package_id;
    public $company_id;
    public $order_table_id;
    public $order_id;
    public $plan_name;
    public $job_title;
    public $customer_id;
    public $carrier;
    public $customer_name;
    public $customer_link;
    public $mobile_number;
    public $wholesale_or_retail;
    public $vendor_id;
    public $outgoing_call;
    public $voice_mail;
    public $roaming;


    public function mount($order_table_id)
    {
        $order = Order::find($order_table_id);
        $this->order = $order;
        $this->form->fill([
            'vendor_id' => $this->order->vendor_id,
            'order_table_id'=> $order_table_id,
            // 'order_id' =>$this->order->order_id,
            'vendor_account_id' => $this->order->vendor_account_id,
            'retail_package_id' => $this->order->retail_package_id,
            'customer_id' => $this->order->customer_id,
            'wholesale_or_retail' => $this->order->wholesale_or_retail,
            'mobile_number' => $this->order->mobile_number,

        ]);
    }



    protected function getFormSchema(): array
    {
        return [
            Wizard::make([
                Wizard\Step::make('Account Details')
                    ->columns(2)
                        ->schema([
                            Forms\Components\Hidden::make('order_table_id')->required(),
                            Forms\Components\Select::make('vendor_id')
                                ->label("Account Name")
                                // ->required()

                                // ->hint('[Forgotten your password?](forgotten-password)')
                                // ->helperText('Your full name here, including any middle names.')
                                ->options([
                                    '1' => '60000500 Aussiecom',
                                ]),
                            Forms\Components\Select::make('vendor_account_id')
                                ->label("Site")
                                // ->required()
                                ->options([
                                    'default' => 'Default'
                                ]),
                        ]),
                Wizard\Step::make('Service Details')
                    ->columns(2)
                        ->schema([
                            Forms\Components\Select::make('customer_id')
                            ->label("Customer")
                            // ->required()
                            ->hintColor('primary')
                            ->hintIcon('heroicon-s-plus-circle')
                            ->hint(new HtmlString('<a href="/customers/create"><b>create customer</b></a>'))
                            // ->hint(view('filament.forms.components.customer-link'))
                            ->searchable()
                            ->options(function () {
                                return  Customer::where('reseller_id',auth()->user()->id)->where('disable_account',0)->with('customercontactinfos')->get()->pluck('customercontactinfos.display_name','id');
                            }),
                            Forms\Components\Select::make('retail_package_id')
                                ->label("Service Package")
                                // ->required()
                                ->searchable()
                                ->options(function () {
                                    return  MobilePlans::all()->pluck('plan_name','id');;
                            }),
                            // Forms\Components\ViewField::make('customer_link')
                            //     ->view('filament.forms.components.customer-link'),
                            Forms\Components\Select::make('wholesale_or_retail')
                                ->label("Wholesale/Retail")
                                ->options([
                                    '0' => 'Retail',
                                    '1' => 'Wholesale',
                            ]),
                        ]),
                Wizard\Step::make('SIM Details')
                    ->columns(2)
                        ->schema([
                            Forms\Components\TextInput::make('mobile_number')
                                ->label("Mobile Number")
                                // ->required()
                                ->autofocus()
                                ->columnSpan(1),
                ]),
                Wizard\Step::make('Service Configuration')
                    ->columns(6)
                    ->schema([
                        Forms\Components\Toggle::make('outgoing_call')->inline(false),
                        Forms\Components\Toggle::make('voice_mail')->inline(false),
                        Forms\Components\Toggle::make('roaming')->inline(false),
                    ]),
            ])
            ->submitAction(new HtmlString(html: '<button class="inline-flex btn btn-sm indigo" wire:loading.attr="disabled" type="submit">
            <span wire:loading.remove="">Configure Package</span>
            <span wire:loading="">Creating
                        <svg class="inline-flex h-4 ml-3 align-middle" viewBox="0 0 135 140" xmlns="http://www.w3.org/2000/svg" fill="currentColor">
            <rect y="10" width="15" height="120" rx="6">
                <animate attributeName="height" begin="0.5s" dur="1s" values="120;110;100;90;80;70;60;50;40;140;120" calcMode="linear" repeatCount="indefinite"></animate>
                <animate attributeName="y" begin="0.5s" dur="1s" values="10;15;20;25;30;35;40;45;50;0;10" calcMode="linear" repeatCount="indefinite"></animate>
            </rect>
            <rect x="30" y="10" width="15" height="120" rx="6">
                <animate attributeName="height" begin="0.25s" dur="1s" values="120;110;100;90;80;70;60;50;40;140;120" calcMode="linear" repeatCount="indefinite"></animate>
                <animate attributeName="y" begin="0.25s" dur="1s" values="10;15;20;25;30;35;40;45;50;0;10" calcMode="linear" repeatCount="indefinite"></animate>
            </rect>
            <rect x="60" width="15" height="140" rx="6">
                <animate attributeName="height" begin="0s" dur="1s" values="120;110;100;90;80;70;60;50;40;140;120" calcMode="linear" repeatCount="indefinite"></animate>
                <animate attributeName="y" begin="0s" dur="1s" values="10;15;20;25;30;35;40;45;50;0;10" calcMode="linear" repeatCount="indefinite"></animate>
            </rect>
            <rect x="90" y="10" width="15" height="120" rx="6">
                <animate attributeName="height" begin="0.25s" dur="1s" values="120;110;100;90;80;70;60;50;40;140;120" calcMode="linear" repeatCount="indefinite"></animate>
                <animate attributeName="y" begin="0.25s" dur="1s" values="10;15;20;25;30;35;40;45;50;0;10" calcMode="linear" repeatCount="indefinite"></animate>
            </rect>
            <rect x="120" y="10" width="15" height="120" rx="6">
                <animate attributeName="height" begin="0.5s" dur="1s" values="120;110;100;90;80;70;60;50;40;140;120" calcMode="linear" repeatCount="indefinite"></animate>
                <animate attributeName="y" begin="0.5s" dur="1s" values="10;15;20;25;30;35;40;45;50;0;10" calcMode="linear" repeatCount="indefinite"></animate>
            </rect>
        </svg>
                    </span>
        </button>'))
        ];
    }


    public function submit()
    {
        $state = $this->form->getState();
        $request = new OrderController();
        $state['wholesale_or_retail'] = $this->checkSelectData($state['wholesale_or_retail']);
        $state['outgoing_call'] = $this->checkToggleData($state['outgoing_call']);
        $state['voice_mail'] = $this->checkToggleData($state['voice_mail']);
        $state['roaming'] = $this->checkToggleData($state['roaming']);
        $data = $request->update($state,$state['order_table_id']);
    }


    private function checkSelectData($value){
        if($value == '0'){
            return 0;
        }
        return 1;
    }

    private function checkToggleData($value){
        if($value == null){
            return 0;
        }
        return 1;
    }

    public function render()
    {
        return view('livewire.orders.edit');
    }
}
