<?php

namespace App\Http\Livewire\Supporttickets;

use Livewire\Component;
use Filament\Forms\Components;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use App\Models\ServiceQualification;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use App\Http\Integrations\APIHub\Requests\SuperloopLocationQualificationRequest;
use App\Models\Tenant;
use App\Models\User;
use App\Models\Customer;
use App\Models\Order;
use Redirect;
use Closure;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Section;
use App\Http\Controllers\SupportTicketController;
use Filament\Notifications\Notification;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Actions\Action;

class Create extends Component implements HasForms
{
    use InteractsWithForms;

    public $description;
    public $fault_category;
    public $customer_id;
    public $mobile_service_order_code;
    public $email;
    public $show_customer_details = false;
    public $customer_details;
    public $order_details;

    public function mount(): void
    {
        if (tenant()) {
            $this->tenant_id = tenant()->id;
        }
    }

    public function onChangeMobileNumber($state)
    {
        if($state != null){
            $this->order_id = $state;
            $this->order_details = Order::find($this->order_id);

            // $this->customer_details = Customer::find($this->customer_id);
            $this->show_customer_details = ! $this->show_customer_details;
        }else{
            $this->customer_id = 0;
            $this->show_customer_details = false;
        }
    }

    protected function getFormSchema(): array
    {
        return [
            Section::make('Create Support Request')
                // ->color("primary")
                ->icon('gmdi-support-agent-r')
                ->description('Seeking Help? Share Your Issue Here.')
                ->schema([

                    Grid::make(4)
                        ->schema([
                            Forms\Components\Select::make('mobile_service_order_code')
                                ->label("Mobile Number")
                                // ->required()
                                ->searchable()
                                ->reactive()
                                ->afterStateUpdated(fn ($state) => $this->onChangeMobileNumber($state))
                                ->options(function () {
                                    return  Order::all()->pluck('mobile_number','id');;
                                }),
                            // Forms\Components\Select::make('customer_id')
                            //     ->label("Customer")
                            //     // ->required()
                            //     ->hintColor('primary')
                            //     // ->hintIcon('heroicon-s-plus-circle')
                            //     // ->hint(new HtmlString('<a href="/customers/create"><b>create customer</b></a>'))
                            //     // ->hint(view('filament.forms.components.customer-link'))
                            //     ->searchable()
                            //     ->options(function () {
                            //         return  Customer::where('reseller_id',auth()->user()->id)->where('disable_account',0)->with('customercontactinfos')->get()->pluck('customercontactinfos.display_name','id');
                            //     }),
                            Forms\Components\Select::make('fault_category')
                                ->label("Fault Category")
                                // ->required()
                                ->default('basic')
                                ->options([
                                    'no_incoming_call' => 'No Incoming call',
                                    'service_down' => 'Service Down',
                                    'no_coverage' => 'No Coverage',
                                    'roaming_not_working' => 'Roaming not working',
                                    'diversion' => 'Diversion',
                                    'other' => 'Other',
                                ]),
                            // Forms\Components\TextInput::make('email')
                            //     ->label("Email")
                            //     // ->required()
                            //     ->autofocus()
                            //     ->columnSpan(1),
                            Forms\Components\Hidden::make('order_details.customer_id'),
                            Forms\Components\Hidden::make('order_details.mobile_number'),
                            Forms\Components\TextInput::make('order_details.customer.customercontactinfos.display_name')
                                ->label("Customer Name")
                                ->hidden(fn (\Filament\Forms\Get $get): bool => $get('show_customer_details') == false),
                            Forms\Components\TextInput::make('order_details.customer.email')
                                ->label("Customer Email")
                                ->hidden(fn (\Filament\Forms\Get $get): bool => $get('show_customer_details') == false),
                        ]),
                    Grid::make(1)
                        ->schema([
                            Forms\Components\Textarea::make('description')
                                ->label("Description")
                                // ->required()
                                ->autofocus()
                                ->columnSpan(1),
                        ])
                ])



        ];
    }

    public function submit()
    {
        $state = $this->form->getState();
        $state['email'] = $state['order_details']['customer']['email'];
        $state['mobile_service_order_code'] = $state['order_details']['mobile_number'];
        $state['customer_id'] = $state['order_details']['customer_id'];
        $request = new SupportTicketController();
        $data = $request->store($state);
    }


    private function checkToggleData($value){
        if($value == null){
            return 0;
        }
        return 1;
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('User updated')
            ->body('The user has been saved successfully.');
    }

    public function render()
    {
        return view('livewire.supporttickets.create');
    }
}
