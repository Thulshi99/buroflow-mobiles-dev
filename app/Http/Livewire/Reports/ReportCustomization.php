<?php

namespace App\Http\Livewire\Reports;

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


class Reportcustomization extends Component implements HasForms
{
    use InteractsWithForms;

    public $date_from;
    public $date_to;

    public $report_type;
    public $format;

     
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
                   
                    Forms\Components\Select::make('report_type')
                        ->label('Report Type')
                        ->options([
                            'portoutreport' => 'Port out report',
                            'detailedpoolreportfromoctane' => 'Detailed pool report from reports in octane',
                            'simcardinventory' => 'Sim card inventory',
                            'allocatedsimbyreseller' => 'Allocated sim by reseller',
                            'unallocatedsimbyreseller' => 'Unallocated sim by reseller',
                            'servicelistingreportbyreseller' => 'Service listing report by reseller',
                            'mobileservicesbilledlast24hrs' => 'Mobile services to get billed in the last 24hrs ',
                            'changestomobileserviceslast24hrs' => 'Last 24hr changes to mobile services',
                        ])
                        ->required()
                        ->autofocus()
                        ->columnSpan(1),
                    Forms\Components\FileUpload::make('upload_new_format')
                        ->label('Upload New Format')
                        ->acceptedFileTypes(['application/pdf']),
                    
                ])
                
        ];
    }

  public function submit()
    {
        
    }
    

    public function render()
    {
        return view('livewire.reports.report-customization');
    }
}

