<?php

namespace App\Http\Livewire\Simcards;

use Livewire\Component;
use Livewire\WithFileUploads;
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
use Closure;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Section;
use App\Http\Controllers\SimCardController;
use Filament\Notifications\Notification;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\FileUpload;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\SimcardImport;
use App\Models\Reseller;
use Filament\Forms\Components\Radio;


class Create extends Component implements HasForms
{
    use InteractsWithForms;
    use WithFileUploads;

    public $file;
    public $reseller_id;
    public $batch_or_single=true;
    public $csv_upload_or_not=false;
    public $is_csv_upload=false;
    public $is_single_add=false;
    public $show_first_last_sim_card=true;
    public $shipvia_id;
    public $first_sim_card_id;
    public $last_sim_card_id;
    public $single_sim_card_id;
    public $date_ordered;
    public $date_received;

    public function mount(): void
    {
    }

    public function onBatchOrSingle($state)
    {
        $this->csv_upload_or_not=false;
        if($state != null){
            $this->is_single_add =!  $this->is_single_add;
            if($state == "0"){
                $this->show_first_last_sim_card = false;
                $this->is_csv_upload = false;
            }else{
                $this->show_first_last_sim_card = true;

            }
        }else{
            $this->is_single_add = false;
        }
    }

    public function onCSVFileUploadOrNot($state)
    {
        if($state != null){
            if($state == "0"){
                $this->show_first_last_sim_card = false;
                $this->is_csv_upload = true;
            }else{
                $this->show_first_last_sim_card = true;
                $this->is_csv_upload = false;
            }
            $this->show_first_last_sim_card =! $this->show_first_last_sim_card;
            $this->is_csv_upload =! $this->is_csv_upload;
        }else{
            $this->is_csv_upload = true;
            $this->show_first_last_sim_card = false;
        }
    }

    protected function getFormSchema(): array
    {
        return [
            Section::make('Upload SIM Card CSV')
                // ->color("primary")
                ->icon('ri-upload-cloud-line')
                ->description('upload your SIM cards csv sheet here')
                // ->columns(2)

                ->schema([
                    Grid::make(2)
                    ->schema([
                        Forms\Components\Radio::make('batch_or_single')
                            ->label('Batch import ?')
                            ->boolean()
                            ->reactive()
                            ->afterStateUpdated(fn ($state) => $this->onBatchOrSingle($state))
                            ->inline(),
                        Forms\Components\Radio::make('csv_upload_or_not')
                            ->label('Do you have CSV file ?')
                            ->boolean()
                            ->reactive()
                            ->afterStateUpdated(fn ($state) => $this->onCSVFileUploadOrNot($state))
                            ->hidden(fn (Closure $get): bool => $get('is_single_add') == true)
                            ->inline()
                    ]),
                    Grid::make()
                    ->schema([
                        Forms\Components\Select::make('reseller_id')
                            ->label("Reseller")
                            // ->required()
                            ->searchable()
                            ->reactive()
                            // ->afterStateUpdated(fn ($state) => $this->onResellerSelectChange($state))
                            ->options(function () {
                                return  Reseller::all()->pluck('reseller_name','reseller_id');;
                            }),
                        Forms\Components\Select::make('shipvia_id')
                            ->label("Carrier")
                            ->default('1')
                            ->options([
                                '1' => 'Telstra',
                                '2' => 'Symbio',
                        ]),
                        Forms\Components\DatePicker::make('date_ordered')
                            ->label("Order Date"),
                        Forms\Components\DatePicker::make('date_received')
                            ->label("Receive Date"),
                        Forms\Components\TextInput::make('first_sim_card_id')
                            ->label("First SIM card ID")
                            ->numeric()
                            ->hidden( fn (Closure $get): bool => $get('show_first_last_sim_card') == false),
                        Forms\Components\TextInput::make('last_sim_card_id')
                            ->label("Last SIM card ID")
                            ->numeric()
                            ->hidden( fn (Closure $get): bool => $get('show_first_last_sim_card') == false),
                        Forms\Components\TextInput::make('single_sim_card_id')
                            ->label("SIM card ID")
                            ->numeric()
                            ->hidden( fn (Closure $get): bool => $get('is_single_add') == false),
                    ]),
                    Grid::make(1)
                    ->schema([
                        Forms\Components\FileUpload::make('file')
                        ->panelAspectRatio('5:1')
                        ->hidden(fn (Closure $get): bool => $get('is_csv_upload') == false)
                        // ->hidden(fn (Closure $get): bool => $get('is_single_add') == true)
                        ->acceptedFileTypes(['application/vnd.ms-excel','application/ms-excel','application/msexcel','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet','excel/*','text/csv','application/csv','text/plain'])
                        ->disableLabel(),
                    ])
                ])




        ];
    }

    public function submit()
    {
        $state = $this->form->getState();
        $request = new SimCardController();
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
        return view('livewire.simcards.create');
    }
}
