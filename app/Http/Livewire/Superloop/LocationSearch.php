<?php

namespace App\Http\Livewire\Superloop;

use Filament\Forms;
use Livewire\Component;
use Illuminate\Support\Arr;
use Filament\Forms\Components;
use Filament\Forms\Components\Grid;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Support\Facades\Redirect;
use Filament\Forms\Concerns\InteractsWithForms;
use App\Http\Integrations\APIHub\Requests\{SuperloopSearchLocationRequest, SuperloopLocationRequestResults};

class LocationSearch extends Component implements HasForms
{
    use InteractsWithForms;

    public $sourceType = 'nbn';
    public $searchType = 'address';
    public $streetNumber;
    public $streetName;
    public $streetType;
    public $streetTypeSuffix;
    public $suburb;
    public $state;
    public $countryCode = 'AU';
    public $postCode;

    public $locIds = [];

    public $locId;

    protected $listeners = ['autocompleted'];

    public function render()
    {
        return view('livewire.superloop.location-search');
    }

    protected function getForms(): array
    {
        return [
            'searchForm' => $this->makeForm()
                ->schema($this->getSearchFormSchema()),
            'locIdForm' => $this->makeForm()
                ->schema($this->getLocIdFormSchema()),
        ];
    }

    protected function getSearchFormSchema(): array
    {
        return [
            Grid::make()
                ->schema([
                    Components\Select::make('sourceType')->required()->columnSpan(6)
                        ->options([
                            'nbn' => 'NBN'
                        ])->default('nbn')
                        ->disablePlaceholderSelection()
                        ->disabled(),
                    // Components\Select::make('searchType')->required()->columnSpan(6)
                    //     ->options([
                    //         'address' => 'Address search'
                    //     ])->default('address')
                    //     ->disablePlaceholderSelection()
                    //     ->disabled(),
                    Components\Hidden::make('searchType')->required()->columnSpan(6)
                       ->default('address'),
                    Components\TextInput::make('streetNumber')->columnSpan([
                        'default' => 6,
                        'lg' => 1,
                    ])->disabled(),
                    Components\TextInput::make('streetName')->required()->columnSpan(6)
                        ->hint('Start typing the address here to auto-complete.')
                        ->placeholder('Start typing here...')
                        ->label('Find your address')
                        ->autofocus(),
                    Components\TextInput::make('streetType')->columnSpan([
                        'default' => 6,
                        'lg' => 3,
                    ])->disabled(),
                    Components\TextInput::make('streetTypeSuffix')->columnSpan([
                        'default' => 6,
                        'lg' => 2,
                    ])->disabled(),
                    Components\TextInput::make('suburb')->required()->columnSpan([
                        'default' => 6,
                        'lg' => 5,
                    ])->disabled(),
                    Components\Select::make('state')->columnSpan([
                        'default' => 6,
                        'lg' => 3,
                    ])  ->disabled()
                        ->placeholder("Select a state")
                        ->required()
                        ->options([
                            'NSW' => 'New South Wales',
                            'QLD' => 'Queensland',
                            'NT' => 'Northern Territory',
                            'VIC' => 'Victoria',
                            'SA' => 'South Australia',
                            'TAS' => 'Tasmania',
                            'ACT' => 'Australian Capital Territory',
                            'WA' => 'Western Australia',
                        ]),
                    Components\Select::make('countryCode')
                        ->label('Country')
                        ->columnSpan([
                            'default' => 6,
                            'lg' => 2,
                        ])
                        ->options(['AU' => 'Australia'])
                        ->default('AU')
                        ->disablePlaceholderSelection()
                        ->disabled(),
                    Components\TextInput::make('postCode')->columnSpan([
                        'default' => 6,
                        'lg' => 2,
                    ])
                        ->numeric(),
                ])
                ->columns(12)
        ];
    }

    /**
     * Updates the livewire properties based on the autofilled values
     *
     * @param  array $address
     * @return void
     */
    public function autocompleted($address): void
    {
        $this->reset(['locIds']);
        foreach ($address as $attribute => $value) {
            $this->{$attribute} = $value;
        }
        
    }

    protected function getLocIdFormSchema(): array
    {
        return [
            Grid::make()
                ->schema([
                    Components\Select::make('locId')->columnSpan(12)
                        ->placeholder('Select a location from the list below')
                        ->options(function () {
                            return $this->locIds;
                        })->searchable(),
                ])
        ];
    }

    public function addressSearch()
    {
        $this->reset(['locIds']);

        $state = $this->searchForm->getState();
        $request = new SuperloopSearchLocationRequest();

        $request->mergeData($state);

        $response = $request->send();

        $this->getLocationIdList($response->json('data.url'));
    }

    private function getLocationIdList($url)
    {
        $poll = new SuperloopLocationRequestResults($url);
        $result = $poll->send();
        $locIds = (array) Arr::pluck($result->json("data"), 'description', 'id');
        $this->locIds = Arr::sort($locIds);
    }

    public function selectLocation()
    {
        Redirect::route('sq.qualify', ['locId' => $this->locId]);
    }

    public function resetForm()
    {
        $this->reset();
    }
}
