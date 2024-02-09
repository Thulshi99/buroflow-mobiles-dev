<?php

namespace App\Http\Livewire\Customers;

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
use Filament\Infolists;
use Filament\Infolists\Infolist;


class ViewCustomer extends Component implements HasForms
{
    use InteractsWithForms;

    public $customer;
    public $customer_id;
    public $email;
    public $customer_code;
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
    public $pay_to_parent;


    public function mount($customer_id)
    {
        $customer = Customer::find($customer_id);
        $this->customer = $customer;
        $this->form->fill([
            'customer_code' => $this->customer->customer_code,
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
            'customer_id'=>$customer_id
        ]);
    } 



    protected function getFormSchema(): array
    {
        return [
            Section::make('Customer Details')
            ->description('customer personal details')
            ->aside()
            ->schema([
                Forms\Components\Hidden::make('customer_id')->required(),
                    Forms\Components\TextInput::make('customer_code')
                        ->label("Customer Code")
                        ->autofocus()
                        ->columnSpan(1),
                    Forms\Components\Select::make('company_id')
                        ->label("Company")
                        ->default('basic')
                        ->options([
                            'basic' => 'Bitzify Sri Lanka',
                            'gold' => 'Buroserv Australia',
                            'platinum' => 'Bitzify Australia',
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
                        ->autofocus()
                        ->columnSpan(1),
                    Forms\Components\TextInput::make('current_phone_number')
                        ->label('Current Phone Number')
                        ->autofocus()
            ])
            ->collapsible(),
            // Section::make('Account Settings')
            // ->description('set customer account settings')
            // ->schema([
            //     Grid::make(4)
            //     ->schema([
            //         Forms\Components\Toggle::make('allow_override_rate')->inline(false)->default(false),
            //         Forms\Components\Toggle::make('payments_allowed')->inline(false),
            //         Forms\Components\Toggle::make('auto_apply_payments')->inline(false),
            //         Forms\Components\Toggle::make('print_statements')->inline(false),
            //         Forms\Components\Toggle::make('send_statement_by_email')->inline(false),
            //         Forms\Components\Toggle::make('shared_credit_policy')->inline(false),
            //         Forms\Components\Toggle::make('consolidate_statements')->inline(false),
            //         Forms\Components\Toggle::make('fin_change_apply')->inline(false),
            //         Forms\Components\Toggle::make('pay_to_parent')->inline(false),
            //     ])
            // ])
            // ->collapsible(),
            // Section::make('Billing Details')
            // ->description('set customer credit details')
            // ->schema([
            //     Grid::make(2)
            //     ->schema([
            //         Forms\Components\Select::make('def_bill_address_id')
            //             ->label("Billing Address")
            //             ->required()
            //             ->default('basic')
            //             ->options([
            //                 'basic' => '300/74,Bitzify Sri Lanka',
            //                 'gold' => '1234,Buroserv Australia',
            //                 'platinum' => '4759/2,Bitzify Australia',
            //             ]),
            //     ])
            // ])
            // ->collapsible(),

            
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
        $state['pay_to_parent'] = $this->checkToggleData($state['pay_to_parent']);
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
        return view('livewire.customers.show');
    }
}
