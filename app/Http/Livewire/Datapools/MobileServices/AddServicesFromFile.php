<?php

namespace App\Http\Livewire\Datapools\MobileServices;

use App\Imports\MobileServicesImport;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Redirect;
use Livewire\Component;
use Filament\Forms\Components\Section;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Notifications\Notification;

class AddServicesFromFile extends Component implements HasForms
{
    use InteractsWithForms;
    use WithFileUploads;


    public $file;

    public $tenant_id;

    public $datapool_id;


    public function mount($datapool_id): void
    {
        if (tenant()) {
            $this->tenant_id = tenant()->id;
        }

        // Use the $datapool_id parameter to perform actions or retrieve data pool
        $this->datapool_id = $datapool_id;
    }

    protected function getFormSchema(): array
    {
        return [
            Section::make('Upload Mobile Services Excel')
                // ->color("primary")
                //->icon('ri-upload-cloud-line')
                ->description('upload your mobile services to be added excel sheet here')
                ->schema([
                    Forms\Components\FileUpload::make('file')
                                    ->label('File')
                                    ->acceptedFileTypes(['application/vnd.ms-excel','application/ms-excel','application/msexcel','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet','excel/*'])
                                    ->disableLabel(),
            ])

        ];
    }

    public function render()
    {
        return view('livewire.datapools.mobile-services.add-services-from-file');
    }


    public function submit()
    {
        $state = $this->form->getState();

        $file_path = storage_path('app/public/'.$state['file']);
        Excel::import(new MobileServicesImport, $file_path);
        return redirect()->route('datapools.manage', ['id' => $this->datapool_id]);

        // Excel::import(new MobileServicesImport, $csv);
        // return redirect()->route('datapools.manage', ['id' => $this->datapool_id]);
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

}
