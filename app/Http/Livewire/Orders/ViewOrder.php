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
use Redirect;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Section;
use App\Http\Controllers\CustomerController;
use Filament\Infolists;
use Filament\Infolists\Infolist;


class ViewOrder extends Component implements HasForms
{
    use InteractsWithForms;

    public $isOpen = false;
    public $record;
    protected $listeners = ['openModal' => 'load'];

    public function load($recordId)
    {
        $this->record = Order::findOrFail($recordId);
        $this->isOpen = true;

    }


    public function render()
    {
        return view('livewire.orders.view-order');
    }
}
