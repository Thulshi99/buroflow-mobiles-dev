<?php

namespace App\Http\Livewire;

use App\Models\User;
use Filament\Tables;
use Filament\Tables\Actions\LinkAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\MultiSelectFilter;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use DB;
use App\Models\Tenant;

class ListUsers extends Component implements Tables\Contracts\HasTable
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
        if (tenant()) {
            $this->tenant_id = tenant()->id;
        }
        $tenant = $this->tenant_id;
        tenancy()->initialize($tenant);

        if(auth()->user()->id == 1){
            $users = User::get()->toQuery();
        }else{
            $users = User::where('id', auth()->user()->id);
        }
        return $users;
    }

    protected function getTableColumns(): array
    {
        return [
            ImageColumn::make('profile_photo_url')->label('Avatar')->rounded()
                ->extraAttributes(['class' => 'p-px text-sm w-12']),
            TextColumn::make('name')->label('User Name')
                ->extraAttributes(['class' => 'p-px text-sm'])
                ->sortable()
                ->searchable(),
            TextColumn::make('email')->label('Email')
                ->extraAttributes(['class' => 'p-px text-sm'])
                ->sortable(),
            TextColumn::make('tenant_role')->label('Role')
                ->extraAttributes(['class' => 'p-px text-sm'])
                ->sortable(),
            // TextColumn::make('currentTeam.name')->label('Team')
            //     ->extraAttributes(['class' => 'p-px text-sm'])
            //     ->default('N/A')->sortable(),
            // TextColumn::make('role.name')->label('Team Role')
            //     ->extraAttributes(['class' => 'p-px text-sm'])
            //     ->default('N/A')->sortable(),
            TextColumn::make('created_at')->label('Created')
                ->extraAttributes(['class' => 'p-px text-sm'])
                ->dateTime('D jS M H:i:s')->sortable(),
            TextColumn::make('updated_at')->label('Updated')
                ->extraAttributes(['class' => 'p-px text-sm'])
                ->dateTime('D jS M H:i:s')->sortable(),
        ];
    }

    protected function getTableFilters(): array
    {
        return [
            MultiSelectFilter::make('currentTeam')->relationship('currentTeam', 'name'),
        ];
    }
    
    protected function getTableActions(): array
    {
        return [
          //  LinkAction::make('edit')->label('Edit User')
          //      ->url(fn (User $record): string => route('users', $record)),
        ];
    }

    public function render()
    {
        return view('livewire.list-table');
    }
    
}
