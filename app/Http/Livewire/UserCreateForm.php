<?php

namespace App\Http\Livewire;

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
use Redirect;
use Illuminate\Support\Facades\Hash;

class UserCreateForm extends Component implements HasForms
{
    use InteractsWithForms;

    public $name;
    public $email;
    public $password;

    public function mount(): void
    {
        if (tenant()) {
            $this->tenant_id = tenant()->id;
        }
    }

    protected function getFormSchema(): array
    {
        return [
            Grid::make()
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->label("Name")
                        ->required()
                        ->autofocus()
                        ->columnSpan(1),
                    Forms\Components\TextInput::make('email')
                        ->label('Email Address')
                        ->required()
                        ->autofocus()
                        ->columnSpan(1),
                    Forms\Components\TextInput::make('password')
                        ->label('Password')
                        ->required()
                        ->autofocus()
                        ->password(),
                ])
        ];
    }

    public function submit()
    {
        $state = $this->form->getState();
        $name = $state['name'];
        $email = $state['email'];
        $password = $state['password'];

        if($this->tenant_id != 'admin')
        {
            //duplicate data to Admin
            tenancy()->initialize('admin');
            User::create([
                "name" => $name,
                "email" => $email,
                "password" => Hash::make($password),
                "tenant_role" => 'user'
            ]);
            tenancy()->end();
        }

        if($this->tenant_id == 'admin')
        {

            $current_team_id = 1;
        }
        else{
            $current_team_id = 0;
        }

        if ($state) {
            $tenant_id = $this->tenant_id;
            $tenant = Tenant::find($tenant_id);

            $tenant->run(function () use ($name, $email, $password, $current_team_id) {
                User::create([
                    "name" => $name,
                    "email" => $email,
                    "password" => Hash::make($password),
                    "tenant_role" => 'user',
                    "current_team_id" => $current_team_id
                ]);
            });
            return Redirect::to('/users');
        }
    }

    public function render()
    {
        return view('livewire.user-create-form');
    }
}
