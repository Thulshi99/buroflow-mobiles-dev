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
use App\Models\Customer;
use Redirect;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Section;
use App\Http\Controllers\CustomerController;
use Illuminate\Support\HtmlString;

class EditSupportticket extends Component implements HasForms
{
    use InteractsWithForms;

    public $email;
    public $first_name;
    public $mid_name;
    public $last_name;
    public $website;
    public $gender;
    public $current_phone_number;
    public $company_id;
    public $primary_contact_name;
    public $job_title;
    public $allow_override_rate;
    public $payments_allowed;
    public $auto_apply_payments;
    public $print_statements;
    public $send_statement_by_email;
    public $shared_credit_policy;
    public $consolidate_statements;
    public $fin_change_apply;
    public $line_one;
    public $line_two;
    public $line_three;
    public $city;
    public $state;
    public $country;
    public $postal_code;
    public $is_billing =true;
    public $small_balance_allow;
    public $date_of_birth;


    public function mount($customer_id)
    {
        $customer = Customer::With('customercontactinfos')->with('addresses')->find($customer_id);
        $this->customer = $customer;
        $this->form->fill([
            'customer_code' => $this->customer->customer_code,
            'customer_id' => $customer_id,
            'primary_contact_name' => $this->customer->primary_contact_name,
            'email' => $this->customer->email,
            'company_id' => $this->customer->company_id,
            'job_title' => $this->customer->job_title,
            'current_phone_number' => $this->customer->current_phone_number,
            'allow_override_rate' => $this->customer->allow_override_rate,
            'payments_allowed' => $this->customer->payments_allowed,
            'auto_apply_payments' => $this->customer->auto_apply_payments,
            'print_statements' => $this->customer->print_statements,
            'send_statement_by_email' => $this->customer->send_statement_by_email,
            'shared_credit_policy' => $this->customer->shared_credit_policy,
            'consolidate_statements' => $this->customer->consolidate_statements,
            'fin_change_apply' => $this->customer->fin_change_apply,
            'pay_to_parent' => $this->customer->pay_to_parent,
            'first_name'=>$this->customer->customercontactinfos->first_name,
            'mid_name'=>$this->customer->customercontactinfos->mid_name,
            'last_name'=>$this->customer->customercontactinfos->last_name,
            'gender'=>$this->customer->customercontactinfos->gender,
            'website'=>$this->customer->customercontactinfos->website,
            'line_one'=>$this->customer->addresses->line_one,
            'line_two'=>$this->customer->addresses->line_two,
            'line_three'=>$this->customer->addresses->line_three,
            'city'=>$this->customer->addresses->city,
            'state'=>$this->customer->addresses->state,
            'country'=>$this->customer->addresses->country,
            'postal_code'=>$this->customer->addresses->postal_code,
        ]);
    }



    protected function getFormSchema(): array
    {
        return [
            Wizard::make([
                Wizard\Step::make('Customer Details')
                    ->columns(4)
                    ->schema([
                        Forms\Components\Hidden::make('customer_id')->required(),
                        Forms\Components\TextInput::make('first_name')
                            ->label("First Name")
                            // ->required()
                            ->autofocus()
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('mid_name')
                            ->label("Mid Name")
                            // ->required()
                            ->autofocus()
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('last_name')
                            ->label("Last Name")
                            // ->required()
                            ->autofocus()
                            ->columnSpan(1),
                        Forms\Components\Select::make('gender')
                            ->label("Gender")
                            // ->required()
                            ->default('basic')
                            ->options([
                                'female' => 'Female',
                                'male' => 'Male',
                            ]),
                        Forms\Components\TextInput::make('website')
                            ->label("Website")
                            // ->required()
                            ->autofocus()
                            ->columnSpan(1),
                        Forms\Components\Select::make('company_id')
                            ->label("Company")
                            // ->required()
                            ->default('basic')
                            ->options([
                                '0' => 'Bitzify Sri Lanka',
                                '1' => 'Buroserv Australia',
                                '2' => 'Bitzify Australia',
                            ]),
                        Forms\Components\TextInput::make('primary_contact_name')
                            ->label("Primary Contact Name")
                            ->autofocus()
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('job_title')
                            ->label("Job Title")
                            ->autofocus()
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('email')
                            ->label('Email Address')
                            ->email()
                            // ->required()
                            ->autofocus()
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('current_phone_number')
                            ->label('Current Phone Number')
                            ->autofocus(),
                        // Forms\Components\DatePicker::make('date_of_birth')
                        //     ->label('Date of Birth')
                        //     ->default(now())
                    ]),
                Wizard\Step::make('Address Details')
                    ->columns(4)
                    ->schema([
                        Forms\Components\TextInput::make('line_one')
                        ->label("Address Line One")
                        // ->required()
                        ->autofocus()
                        ->columnSpan(1),
                    Forms\Components\TextInput::make('line_two')
                        ->label("Address Line Two")
                        // ->required()
                        ->autofocus()
                        ->columnSpan(1),
                    Forms\Components\TextInput::make('line_three')
                        ->label("Address Line Three")
                        // ->required()
                        ->autofocus()
                        ->columnSpan(1),
                    Forms\Components\TextInput::make('city')
                        ->label("City")
                        // ->required()
                        ->autofocus()
                        ->columnSpan(1),
                    Forms\Components\TextInput::make('state')
                        ->label("State")
                        // ->required()
                        ->autofocus()
                        ->columnSpan(1),
                    Forms\Components\TextInput::make('country')
                        ->label("country")
                        // ->required()
                        ->autofocus()
                        ->columnSpan(1),
                    Forms\Components\TextInput::make('postal_code')
                        ->label("Postal Code")
                        // ->required()
                        ->autofocus()
                        ->columnSpan(1),
                        Forms\Components\Toggle::make('is_billing')->label("Set As Billing Address")->inline(false),
                ]),
                Wizard\Step::make('Account Settings')
                    ->columns(6)
                    ->schema([
                        Forms\Components\Toggle::make('allow_override_rate')->inline(false)->default(false),
                        Forms\Components\Toggle::make('payments_allowed')->inline(false),
                        Forms\Components\Toggle::make('auto_apply_payments')->inline(false),
                        Forms\Components\Toggle::make('print_statements')->inline(false),
                        Forms\Components\Toggle::make('send_statement_by_email')->inline(false),
                        Forms\Components\Toggle::make('shared_credit_policy')->inline(false),
                        Forms\Components\Toggle::make('consolidate_statements')->inline(false),
                        Forms\Components\Toggle::make('fin_change_apply')->inline(false),
                        Forms\Components\Toggle::make('small_balance_allow')->inline(false),
                ])
            ])
            ->submitAction(new HtmlString(html: '<button class="inline-flex btn btn-sm indigo" wire:loading.attr="disabled" type="submit">
            <span wire:loading.remove="">Update Customer</span>
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
        $state['allow_override_rate'] = $this->checkToggleData($state['allow_override_rate']);
        $state['payments_allowed'] = $this->checkToggleData($state['payments_allowed']);
        $state['auto_apply_payments'] = $this->checkToggleData($state['auto_apply_payments']);
        $state['print_statements'] = $this->checkToggleData($state['print_statements']);
        $state['send_statement_by_email'] = $this->checkToggleData($state['send_statement_by_email']);
        $state['shared_credit_policy'] = $this->checkToggleData($state['shared_credit_policy']);
        $state['consolidate_statements'] = $this->checkToggleData($state['consolidate_statements']);
        $state['fin_change_apply'] = $this->checkToggleData($state['fin_change_apply']);
        $state['small_balance_allow'] = $this->checkToggleData($state['small_balance_allow']);
        $state['is_billing'] = $this->checkToggleData($state['is_billing']);
        $request = new CustomerController();
        $data = $request->update($state,$state['customer_id']);
    }


    private function checkToggleData($value){
        if($value == null){
            return 0;
        }
        return 1;
    }

    public function render()
    {
        return view('livewire.supporttickets.edit');
    }
}
