<?php
namespace App\Http\Livewire\Reports;

use App\Http\Controllers\ReportController;
use App\Models\Reseller;
use Closure;
use Doctrine\DBAL\Driver\Mysqli\Initializer\Options;
use Filament\Forms\Components\DatePicker;
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
use PhpOption\Option;
use Redirect;
use Illuminate\Support\Facades\Hash;
use App\Actions\ResetStars;
use Filament\Forms\Components\Button;
use Filament\Forms\Components\Actions\Action;
use Illuminate\Support\Facades\Mail;
use APP;
use App\Mail\ReportMail;
use App\Models\MobileService;
use App\Models\Customer;
use App\Models\ResellerMobileChangeLog;
use App\Models\ResellerMobileChangeLogs;
use App\Models\SimCard;
use Artisaninweb\SoapWrapper\SoapWrapper;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Reports;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\File;
use App\Mail\SendPdf;
use Filament\Notifications\Notification;



class reportgeneration extends Component implements HasForms {
    use InteractsWithForms;
    public $send_email;
    public $date_from;
    public $date_to;
    public $report_type;
    public $format;
    public $reseller_name;
    public $emails;
    public $action;
    public $hide_dates=false;
    const REPORT_SIM_CARDS = 3;
    const REPORT_ALLOCATED_SIM = 4;
    const REPORT_UNALLOCATED_SIM = 5;
    const REPORT_SERVICE_DETAIL = 6;
    const REPORT_MOBILE_SERVICES = 7;
    const UPDATE_REPORT = 8;
    const FORMAT_PDF = 'pdf';
    const FORMAT_CSV = 'csv';

    public function mount(): void {
        if(tenant()) {
            $this->tenant_id = tenant()->id;
        }
    }

    public function hideDateField($state){
        if($state != null)
        {
            $this->report_type = $state;
            if($state == "7" || $state == "8")
            {
                $this->hide_dates=true;
            }  
            else{
                $this->hide_dates = false;
            }
        }
        else
        {
            $this->hide_dates = false;
        }
    }

    public function resellerOption($state){
        
        if($state != null)
        {
            $this->report_type = $state;
            if($state == "6"){
                $resellerOption = Reseller::where('retail_billing', 'Yes')->pluck('reseller_name', 'reseller_id')->toArray();
            } else {
                
                $resellerOption = Reseller::pluck('reseller_name', 'reseller_id')->toArray();
            }
            return $resellerOption;
        }
    }
    protected function getFormSchema(): array {

        $reportTypeOptions = [
            '1' => 'PORT OUT REPORT',
            '2' => 'DETAILED POOL REPORT FROM REPORTS IN OCTAN',
            '3' => 'SIM CARD INVENTORY',
            '4' => 'ALLOCATED SIM BY RESELLER',
            '5' => 'UNALLOCATED SIM BY RESELLER',
            '6' => 'SERVICE LISTING REPORT BY RESELLER',
            '7' => 'MOBILE SERVICES TO GET BILLED IN THE LAST 24HRS ',
            '8' => 'LAST 24HR CHANGES TO MOBILE SERVICES',
        ];

        if($this->tenant_id != 'admin' && Reseller::where('reseller_id', $this->tenant_id)->value('retail_billing') === 'No') 
        {
            unset($reportTypeOptions['6']);
        }

        return [
            Grid::make()
                ->schema([
                    Forms\Components\Select::make('report_type')
                        ->label('Report Type')
                        ->options($reportTypeOptions)
                        ->required()
                        ->autofocus()
                        ->reactive()
                        ->afterStateUpdated(function ($state) {
                            $this->hideDateField($state);
                            $this->resellerOption($state);
                        })
                        ->columnSpan(1),

                    Forms\Components\Select::make('format')
                        ->label('Report Format')
                        ->options([
                            'pdf' => 'PDF',
                            'csv' => 'CSV'
                        ])
                        ->required()
                        ->autofocus()
                        ->columnSpan(1),

                    Forms\Components\DatePicker::make('date_from')
                        ->label("From")
                        ->required()
                        ->autofocus()
                        ->columnSpan(1)
                        ->maxDate(now())
                        ->hidden(fn (Closure $get): bool => $get('hide_dates')),

                    Forms\Components\DatePicker::make('date_to')
                        ->label('To')
                        ->required()
                        ->autofocus()
                        ->columnSpan(1)
                        ->maxDate(now())
                        ->hidden(fn (Closure $get): bool => $get('hide_dates')),

                    Forms\Components\Select::make('reseller_name')
                        ->label('Reseller')
                        ->reactive()
                        ->options($this->resellerOption($this->report_type))
                        ->autofocus()
                        ->columnSpan(1)
                        ->hidden($this->tenant_id != 'admin'),

                    Forms\Components\Select::make('emails')
                        ->label('Email Address')
                        ->multiple()
                        ->options(User::pluck('email', 'email')->toArray()),

                    Forms\Components\Radio::make('action')
                        ->options([
                            'email' => 'Email',
                            'generate' => 'Generate Report',
                        ])
                        ->required(),
                    // Forms\Components\Actions\Action::make('testAction')
                    //     ->label('Test')
                    //     // ->primary()
                    //     ->action(fn () => $this->submit()),
                ])
               
        ];


    }

    public function submit() {

        $state = $this->form->getState();
        $data = [
            "report_type" => $state['report_type'],
            "format" => $state['format'],
            "date_from" => $state['date_from'],
            "date_to" => $state['date_to'],
            "emails" => $state['emails'],

        ];
        if($state['action'] == "email") {
            if($state['emails'] != null) {
                $maildata = [
                    "email" => $data["emails"],
                    "title" => "Email Testing Title",
                    "body" => "Email Testing Body",
                ];
        
                if ($data['report_type'] == self::REPORT_SIM_CARDS) {
                    $simCards = $this->getUserData($data);
        
                    if (empty($simCards['simCards'])) {
                        Notification::make()
                        ->title('Error')
                        ->body('No data available')
                        ->status('danger') // This sets the notification to be an error notification
                        ->send();
                    } else {
                        if ($data['format'] == self::FORMAT_PDF) {
                            $pdf = App::make('dompdf.wrapper');
                            $pdf = PDF::loadView('pdf.simcardinventory', ['simCards' => $simCards['simCards']]);
        
                            Mail::send('reports.mail', $maildata, function ($message) use ($maildata, $pdf) {
                                $message->to($maildata["email"])
                                    ->subject($maildata["title"])
                                    ->attachData($pdf->output(), "test.pdf");
        
                            });
                            return $pdf->stream('Sim Cards.pdf');
                        } else if ($data['format'] == self::FORMAT_CSV) {
                            $csvFileName = storage_path('app/Sim Cards.csv');
                            $csvFile = fopen($csvFileName, 'w');
                            $csvHeader = ['SIM Card Number', 'Created At', 'Status', 'Mobile Number', 'Inventory ', 'Carrier'];
                            fputcsv($csvFile, $csvHeader);
                            foreach ($simCards['simCards'] as $simCard) {
                                fputcsv($csvFile, $simCard);
                            }
                            fclose($csvFile);
        
                            Mail::send('reports.mail', $maildata, function ($message) use ($maildata, $csvFileName) {
                                $message->to($maildata["email"])
                                    ->subject($maildata["title"])
                                    ->attach($csvFileName, [
                                        'as' => 'Sim Cards.csv',
                                        'mime' => 'application/csv',
                                    ]);
                            });
        
                            $response = response()->download($csvFileName, 'Sim Cards.csv');
                            $response->deleteFileAfterSend(true);
        
                            return $response;
                        }
                    }
                }
        
                if ($data['report_type'] == self::REPORT_ALLOCATED_SIM) {
                    $allocated_sim_by_reseller = $this->getUserData($data);
        
                    if (empty($allocated_sim_by_reseller['allocated_sim_by_reseller'])) {
                        Notification::make()
                        ->title('Error')
                        ->body('No data available')
                        ->status('danger') // This sets the notification to be an error notification
                        ->send();
                    } else {
                        if ($data['format'] == self::FORMAT_PDF) {
                            $pdf = App::make('dompdf.wrapper');
                            $pdf->loadView('pdf.allocatedsim', ['allocated_sim_by_reseller' => $allocated_sim_by_reseller['allocated_sim_by_reseller']]);
        
                            Mail::send('reports.mail', $maildata, function ($message) use ($maildata, $pdf) {
                                $message->to($maildata["email"])
                                    ->subject($maildata["title"])
                                    ->attachData($pdf->output(), "Allocated Sim By Reseller.pdf");
                            });
        
                            return $pdf->stream('Allocated Sim By Reseller.pdf');
                        } else if ($data['format'] == self::FORMAT_CSV) {
                            $csvFileName = storage_path('app/Allocated Sim By Reseller.csv');
                            $csvFile = fopen($csvFileName, 'w');
                            $csvHeader = ['SIM Card Number', 'Created At', 'Status', 'Mobile Number', 'Inventory ', 'Carrier'];
                            fputcsv($csvFile, $csvHeader);
                            foreach ($allocated_sim_by_reseller['allocated_sim_by_reseller'] as $allocated_sim) {
                                fputcsv($csvFile, $allocated_sim);
                            }
                            fclose($csvFile);
        
                            Mail::send('reports.mail', $maildata, function ($message) use ($maildata, $csvFileName) {
                                $message->to($maildata["email"])
                                    ->subject($maildata["title"])
                                    ->attach($csvFileName, [
                                        'as' => 'Allocated Sim By Reseller.csv',
                                        'mime' => 'application/csv',
                                    ]);
                            });
        
                            $response = response()->download($csvFileName, 'Allocated Sim By Reseller.csv');
                            $response->deleteFileAfterSend(true);
        
                            return $response;
                        }
                    }
                }
        
                if ($data['report_type'] == self::REPORT_UNALLOCATED_SIM) {
                    $unallocated_sim_by_reseller = $this->getUserData($data);
        
                    if (empty($unallocated_sim_by_reseller['unallocated_sim_by_reseller'])) {
                        Notification::make()
                        ->title('Error')
                        ->body('No data available')
                        ->status('danger') // This sets the notification to be an error notification
                        ->send();
                    } else {
                        if ($data['format'] == self::FORMAT_PDF) {
                            $pdf = App::make('dompdf.wrapper');
                            $pdf->loadView('pdf.unallocatedsim', ['unallocated_sim_by_reseller' => $unallocated_sim_by_reseller['unallocated_sim_by_reseller']]);
        
                            Mail::send('reports.mail', $maildata, function ($message) use ($maildata, $pdf) {
                                $message->to($maildata["email"])
                                    ->subject($maildata["title"])
                                    ->attachData($pdf->output(), "Unallocated Sim By Reseller.pdf");
                            });
        
                            return $pdf->stream('Unallocated Sim By Reseller.pdf');
                        } else if ($data['format'] == self::FORMAT_CSV) {
                            $csvFileName = storage_path('app/Unallocated Sim By Reseller.csv');
                            $csvFile = fopen($csvFileName, 'w');
                            $csvHeader = ['SIM Card Number', 'Created At', 'Status', 'Mobile Number', 'Inventory ', 'Carrier'];
                            fputcsv($csvFile, $csvHeader);
                            foreach ($unallocated_sim_by_reseller['unallocated_sim_by_reseller'] as $unallocated_sim) {
                                fputcsv($csvFile, $unallocated_sim);
                            }
                            fclose($csvFile);
        
                            Mail::send('reports.mail', $maildata, function ($message) use ($maildata, $csvFileName) {
                                $message->to($maildata["email"])
                                    ->subject($maildata["title"])
                                    ->attach($csvFileName, [
                                        'as' => 'Unallocated Sim By Reseller.csv',
                                        'mime' => 'application/csv',
                                    ]);
                            });
        
                            $response = response()->download($csvFileName, 'Unallocated Sim By Reseller.csv');
                            $response->deleteFileAfterSend(true);
        
                            return $response;
                        }
                    }
                }
        
                if ($data['report_type'] == self::REPORT_SERVICE_DETAIL) {
                    $service_listing_repo_by_reseller = $this->getUserData($data);
        
                    if (empty($service_listing_repo_by_reseller['service_listing_repo_by_reseller'])) {
                        Notification::make()
                        ->title('Error')
                        ->body('No data available')
                        ->status('danger') // This sets the notification to be an error notification
                        ->send();
                    } else {
                        if ($data['format'] == self::FORMAT_PDF) {
                            $pdf = App::make('dompdf.wrapper');
                            $pdf->loadView('pdf.servicelistingrepobyreseller', ['service_listing_repo_by_reseller' => $service_listing_repo_by_reseller['service_listing_repo_by_reseller']])->setPaper('landscape');
                            Mail::send('reports.mail', $maildata, function ($message) use ($maildata, $pdf) {
                                $message->to($maildata["email"])
                                    ->subject($maildata["title"])
                                    ->attachData($pdf->output(), "Service Listing.pdf");
                            });
        
                            return $pdf->stream('Service Listing.pdf');
                        } else if ($data['format'] == self::FORMAT_CSV) {
        
                            $csvFileName = storage_path('app/Service Listing.csv');
                            $csvFile = fopen($csvFileName, 'w');
                            $csvHeader = ['Status', 'Order ID', 'Order Date', 'Sim Card', 'Mobile Number', 'Plan', 'End User'];
                            fputcsv($csvFile, $csvHeader);
                            foreach ($service_listing_repo_by_reseller['service_listing_repo_by_reseller'] as $service_listing) {
                                fputcsv($csvFile, $service_listing);
                            }
                            fclose($csvFile);
        
                            Mail::send('reports.mail', $maildata, function ($message) use ($maildata, $csvFileName) {
                                $message->to($maildata["email"])
                                    ->subject($maildata["title"])
                                    ->attach($csvFileName, [
                                        'as' => 'Service Listing.csv',
                                        'mime' => 'application/csv',
                                    ]);
                            });
        
                            $response = response()->download($csvFileName, 'Service Listing.csv');
                            $response->deleteFileAfterSend(true);
        
                            return $response;
                        }
                    }
                }
        
                if ($data['report_type'] == self::REPORT_MOBILE_SERVICES) {
                    $mobile_service = $this->getUserData($data);
        
                    if (empty($mobile_service['mobile_service'])) {
                        Notification::make()
                        ->title('Error')
                        ->body('No data available')
                        ->status('danger') // This sets the notification to be an error notification
                        ->send();
                    } else {
                        if ($data['format'] == self::FORMAT_PDF) {
                            $pdf = App::make('dompdf.wrapper');
                            $pdf->loadView('pdf.mobileservicesbilledlast24hours', ['mobile_service' => $mobile_service['mobile_service']]);
        
                            Mail::send('reports.mail', $maildata, function ($message) use ($maildata, $pdf) {
                                $message->to($maildata["email"])
                                    ->subject($maildata["title"])
                                    ->attachData($pdf->output(), "Mobile Services Billed Past 24 Hours.pdf");
                            });
        
                            return $pdf->stream('Mobile Services Billed Past 24 Hours.pdf');
                        } else if ($data['format'] == self::FORMAT_CSV) {
                            $csvFileName = storage_path('app/Mobile Services Billed Past 24 Hours.csv');
                            $csvFile = fopen($csvFileName, 'w');
                            $csvHeader = ['Mobile Service ID', 'Mobile Number', 'Cost Centre', 'SIM Card Code', 'Reseller Name ', 'Reseller Billing Account No', 'Package Id'];
                            fputcsv($csvFile, $csvHeader);
                            foreach ($mobile_service['mobile_service'] as $mobile) {
                                fputcsv($csvFile, $mobile);
                            }
                            fclose($csvFile);
        
                            Mail::send('reports.mail', $maildata, function ($message) use ($maildata, $csvFileName) {
                                $message->to($maildata["email"])
                                    ->subject($maildata["title"])
                                    ->attach($csvFileName, [
                                        'as' => 'Mobile Services Billed Past 24 Hours.csv',
                                        'mime' => 'application/csv',
                                    ]);
                            });
        
                            $response = response()->download($csvFileName, 'Mobile Services Billed Past 24 Hours.csv');
                            $response->deleteFileAfterSend(true);
        
                            return $response;
                        }
                    }
                }
        
        
                if ($data['report_type'] == self::UPDATE_REPORT) {
                    $mobile_update = $this->getUserData($data);
        
                    if (empty($mobile_update['mobile_update'])) {
                        Notification::make()
                        ->title('Error')
                        ->body('No data available')
                        ->status('danger') // This sets the notification to be an error notification
                        ->send();
                    } else {
                        if ($data['format'] == self::FORMAT_PDF) {
                            $pdf = App::make('dompdf.wrapper');
                            $pdf->loadView('pdf.updatespast24hours', ['mobile_update' => $mobile_update['mobile_update']]);
        
                            Mail::send('reports.mail', $maildata, function ($message) use ($maildata, $pdf) {
                                $message->to($maildata["email"])
                                    ->subject($maildata["title"])
                                    ->attachData($pdf->output(), "Last 24hr Changes To Mobile Services.pdf");
                            });
        
                            return $pdf->stream('Last 24hr Changes To Mobile Services.pdf');
                        } else if ($data['format'] == self::FORMAT_CSV) {
                            $csvFileName = storage_path('app/Last 24hr Changes To Mobile Services .csv');
                            $csvFile = fopen($csvFileName, 'w');
                            $csvHeader = ['Mobile Number', 'SIM Number', 'Package Name', 'Reseller Name', 'Reselling Billing Account Number','What Change'];
                            fputcsv($csvFile, $csvHeader);
                            foreach ($mobile_update['mobile_update'] as $update) {
                                fputcsv($csvFile, $update);
                            }
                            fclose($csvFile);
        
                            Mail::send('reports.mail', $maildata, function ($message) use ($maildata, $csvFileName) {
                                $message->to($maildata["email"])
                                    ->subject($maildata["title"])
                                    ->attach($csvFileName, [
                                        'as' => 'Last 24hr Changes To Mobile Services.csv',
                                        'mime' => 'application/csv',
                                    ]);
                            });
        
                            $response = response()->download($csvFileName, 'Last 24hr Changes To Mobile Services.csv');
                            $response->deleteFileAfterSend(true);
        
                            return $response;
                        }
                    }
                }
                } else {
                $this->addError('emails', 'Emails field is required.');
            }
        } else if($state['action'] == "generate") {
            if ($data['report_type'] == self::REPORT_SIM_CARDS) {
                $simCards = $this->getUserData($data);
                if (empty($simCards['simCards'])) {
                    Notification::make()
                    ->title('Error')
                    ->body('No data available')
                    ->status('danger') // This sets the notification to be an error notification
                    ->send();
                } else {
                    if ($data['format'] == self::FORMAT_PDF) {
                        dd($simCards['simCards']);
                        $pdf = App::make('dompdf.wrapper');
                        $pdf->loadView('pdf.simcardinventory', ['simCards' => $simCards['simCards']]);
                        return $pdf->stream('Sim Cards.pdf');
                    } else if ($data['format'] == self::FORMAT_CSV) {
    
                        $csvFileName = storage_path('app/Sim Cards.csv');
                        $csvFile = fopen($csvFileName, 'w');
                        $csvHeader = ['SIM Card Number', 'Created At', 'Status', 'Mobile Number', 'Inventory ', 'Carrier'];
                        fputcsv($csvFile, $csvHeader);
                        foreach ($simCards['simCards'] as $simCard) {
                            fputcsv($csvFile, $simCard);
                        }
                        fclose($csvFile);
                        return response()->download($csvFileName, 'Sim Cards.csv');
                    }
                }
    
            }
    
            if ($data['report_type'] == self::REPORT_ALLOCATED_SIM) {
                $allocated_sim_by_reseller = $this->getUserData($data);
    
                if (empty($allocated_sim_by_reseller['allocated_sim_by_reseller'])) {
                    Notification::make()
                    ->title('Error')
                    ->body('No data available')
                    ->status('danger') // This sets the notification to be an error notification
                    ->send();                } else {
                    if ($data['format'] == self::FORMAT_PDF) {
                        $pdf = App::make('dompdf.wrapper');
                        $pdf->loadView('pdf.allocatedsim', ['allocated_sim_by_reseller' => $allocated_sim_by_reseller['allocated_sim_by_reseller']]);
                        return $pdf->stream('Allocated Sim By Reseller.pdf');
                    } else if ($data['format'] == self::FORMAT_CSV) {
                        $csvFileName = storage_path('app/Allocated Sim By Reseller.csv');
                        $csvFile = fopen($csvFileName, 'w');
                        $csvHeader = ['SIM Card Number', 'Created At', 'Status', 'Mobile Number', 'Inventory ', 'Carrier'];
                        fputcsv($csvFile, $csvHeader);
                        foreach ($allocated_sim_by_reseller['allocated_sim_by_reseller'] as $allocated_sim) {
                            fputcsv($csvFile, $allocated_sim);
                        }
                        fclose($csvFile);
                        return response()->download($csvFileName, 'Allocated Sim By Reseller.csv');
                    }
    
                }
    
            }
    
            if ($data['report_type'] == self::REPORT_UNALLOCATED_SIM) {
                $unallocated_sim_by_reseller = $this->getUserData($data);
                if (empty($unallocated_sim_by_reseller['unallocated_sim_by_reseller'])) {
                    Notification::make()
                    ->title('Error')
                    ->body('No data available')
                    ->status('danger') // This sets the notification to be an error notification
                    ->send();                } else {
                    if ($data['format'] == self::FORMAT_PDF) {
                        $pdf = App::make('dompdf.wrapper');
                        $pdf->loadView('pdf.unallocatedsim', ['unallocated_sim_by_reseller' => $unallocated_sim_by_reseller['unallocated_sim_by_reseller']]);
                        return $pdf->stream('Unallocated Sim By Reseller.pdf');
                    } else if ($data['format'] == self::FORMAT_CSV) {
                        $csvFileName = storage_path('app/Unallocated Sim By Reseller.csv');
                        $csvFile = fopen($csvFileName, 'w');
                        $csvHeader = ['SIM Card Number', 'Created At', 'Status', 'Mobile Number', 'Inventory ', 'Carrier'];
                        fputcsv($csvFile, $csvHeader);
                        foreach ($unallocated_sim_by_reseller['unallocated_sim_by_reseller'] as $unallocated_sim) {
                            fputcsv($csvFile, $unallocated_sim);
                        }
                        fclose($csvFile);
                        return response()->download($csvFileName, 'Unallocated Sim By Reseller.csv');
                    }
                }
    
    
            }
    
            if ($data['report_type'] == self::REPORT_SERVICE_DETAIL) {
                $service_listing_repo_by_reseller = $this->getUserData($data);
                if (empty($service_listing_repo_by_reseller['service_listing_repo_by_reseller'])) {
                    Notification::make()
                    ->title('Error')
                    ->body('No data available')
                    ->status('danger') // This sets the notification to be an error notification
                    ->send();
                } else {
                    if ($data['format'] == self::FORMAT_PDF) {
                        $pdf = App::make('dompdf.wrapper');
                        $pdf->loadView('pdf.servicelistingrepobyreseller', ['service_listing_repo_by_reseller' => $service_listing_repo_by_reseller['service_listing_repo_by_reseller']])->setPaper('landscape');
                        return $pdf->stream('Service Listing.pdf');
                    } else if ($data['format'] == self::FORMAT_CSV) {
                        $csvFileName = storage_path('app/Service Listing.csv');
                        $csvFile = fopen($csvFileName, 'w');
                        $csvHeader = ['Status', 'Order ID', 'Order Date', 'Sim Card', 'Mobile Number', 'Plan', 'End User'];
                        fputcsv($csvFile, $csvHeader);
                        foreach ($service_listing_repo_by_reseller['service_listing_repo_by_reseller'] as $service_listing) {
                            fputcsv($csvFile, $service_listing);
                        }
                        fclose($csvFile);
                        return response()->download($csvFileName, 'Service Listing.csv');
                    }
                }
            }
    
            if ($data['report_type'] == self::REPORT_MOBILE_SERVICES) {
                $mobile_service = $this->getUserData($data);
                if (empty($mobile_service['mobile_service'])) {
                    Notification::make()
                    ->title('Error')
                    ->body('No data available')
                    ->status('danger') // This sets the notification to be an error notification
                    ->send();                } else {
                    if ($data['format'] == self::FORMAT_PDF) {
                        $pdf = App::make('dompdf.wrapper');
                        $pdf->loadView('pdf.mobileservicesbilledlast24hours', ['mobile_service' => $mobile_service['mobile_service']]);
                        return $pdf->stream('Mobile Services Billed Past 24 Hours.pdf');
                    } else if ($data['format'] == self::FORMAT_CSV) {
                        $csvFileName = storage_path('app/Mobile Services Billed Past 24 Hours.csv');
                        $csvFile = fopen($csvFileName, 'w');
                        $csvHeader = ['Mobile Service ID', 'Mobile Number', 'Cost Centre', 'SIM Card Code', 'Reseller Name ', 'Reseller Billing Account No', 'Package Id'];
                        fputcsv($csvFile, $csvHeader);
                        foreach ($mobile_service['mobile_service'] as $mobile) {
                            fputcsv($csvFile, $mobile);
                        }
                        fclose($csvFile);
                        return response()->download($csvFileName, 'Mobile Services Billed Past 24 Hours.csv');
                    }
                }
    
            }
    
            if ($data['report_type'] == self::UPDATE_REPORT) {
                $mobile_update = $this->getUserData($data);
                if (empty($mobile_update['mobile_update'])) {
                    Notification::make()
                    ->title('Error')
                    ->body('No data available')
                    ->status('danger') // This sets the notification to be an error notification
                    ->send();                } else {
                    if ($data['format'] == self::FORMAT_PDF) {
                        $pdf = App::make('dompdf.wrapper');
                        $pdf->loadView('pdf.updatespast24hours', ['mobile_update' => $mobile_update['mobile_update']]);
                        return $pdf->stream('Last 24hr Changes To Mobile Services.pdf');
                    } else if ($data['format'] == self::FORMAT_CSV) {
                        $csvFileName = storage_path('app/Last 24hr Changes To Mobile Services .csv');
                        $csvFile = fopen($csvFileName, 'w');
                        $csvHeader = ['Mobile Number', 'SIM Number', 'Package Name', 'Reseller Name', 'Reselling Billing Account Number','What Change'];
                        fputcsv($csvFile, $csvHeader);
                        foreach ($mobile_update['mobile_update'] as $update) {
                            fputcsv($csvFile, $update);
                        }
                        fclose($csvFile);
                        return response()->download($csvFileName, 'Mobile Services Billed Past 24 Hours.csv');
                    }
                }
    
            }
            }
    }
    function getUserData(array $data)
    {
        $User = User::find(auth()->user()->id);

        $sim_cards = SimCard::select(
            'simcards.sim_card_code',
            'simcards.created_at',
            'simcards.status',
            'simcards.mobile_number',
            'resellers.reseller_name',
            'shipvias.shipvia_agent_name',
        )
            ->leftJoin('resellers', 'resellers.reseller_id', '=', 'simcards.reseller_id')
            ->leftJoin('orders', 'orders.mobile_number', '=', 'simcards.mobile_number')
            ->leftJoin('shipvias', 'shipvias.id', '=', 'orders.shipvia_id') 
            ->whereBetween('simcards.created_at', [$data['date_from'], date('Y-m-d', strtotime($data['date_to'] . ' + 1 day'))]);

        $allocated_sim_by_resellers = SimCard::select(
            'simcards.sim_card_code',
            'simcards.created_at',
            'simcards.status',
            'simcards.mobile_number',
            'resellers.reseller_name',
            'shipvias.shipvia_agent_name',
        )
            ->leftJoin('resellers', 'resellers.reseller_id', '=', 'simcards.reseller_id')
            ->leftJoin('orders', 'orders.mobile_number', '=', 'simcards.mobile_number')
            ->leftJoin('shipvias', 'shipvias.id', '=', 'orders.shipvia_id') 
            ->where('simcards.status', '=', 'ALLOCATED')
            ->whereBetween('simcards.created_at', [$data['date_from'], date('Y-m-d', strtotime($data['date_to'] . ' + 1 day'))]);

        $unallocated_sim_by_resellers = SimCard::select(
            'simcards.sim_card_code',
            'simcards.created_at',
            'simcards.status',
            'simcards.mobile_number',
            'resellers.reseller_name',
            'shipvias.shipvia_agent_name',
        )
            ->leftJoin('resellers', 'resellers.reseller_id', '=', 'simcards.reseller_id')
            ->leftJoin('orders', 'orders.mobile_number', '=', 'simcards.mobile_number')
            ->leftJoin('shipvias', 'shipvias.id', '=', 'orders.shipvia_id') 
            ->where('simcards.status', '=', 'AVAILABLE')
            ->whereBetween('simcards.created_at', [$data['date_from'], date('Y-m-d', strtotime($data['date_to'] . ' + 1 day'))]);

        $service_listing = MobileService::select(
            'mobile_services.service_status',
            'mobile_services.order_id',
            'mobile_services.created_at',
            'simcards.sim_card_code',
            'mobile_services.mobile_number',
            'retail_packages.retail_pakage_name',
            'mobile_services.end_user_name',

        )
            ->leftJoin('simcards', 'simcards.mobile_number', '=', 'mobile_services.mobile_number')
            ->leftJoin('retail_packages', 'retail_packages.id', '=', 'mobile_services.retail_package_id')
            ->leftJoin('resellers', 'resellers.reseller_id', '=', 'mobile_services.reseller_id')
            ->where('resellers.retail_billing', '=', 'Yes')
            ->whereBetween('mobile_services.created_at', [$data['date_from'], date('Y-m-d', strtotime($data['date_to'] . ' + 1 day'))]);

        $mobile_services = MobileService::select(
            'mobile_services.mobile_service_id',
            'mobile_services.mobile_number',
            'mobile_services.cost_centre',
            'simcards.sim_card_code',
            'resellers.reseller_name',
            'resellers.reseller_billing_account_no',
            DB::raw('CASE WHEN mobile_services.wholesale_or_retail = "wh" THEN mobile_services.wholesale_package_id ELSE mobile_services.retail_package_id END AS package_id')
        )
            ->leftJoin('simcards', 'simcards.mobile_number', '=', 'mobile_services.mobile_number')
            ->leftJoin('resellers', 'resellers.reseller_id', '=', 'mobile_services.reseller_id')
            ->where('mobile_services.service_status', '=', 'COMPLETED')
            ->where('mobile_services.created_at', '>=', Carbon::now()->subHours(24))
            ->groupBy('mobile_services.mobile_service_id');

        $mobile_updates = MobileService::select(
            'mobile_services.mobile_number',
            'simcards.sim_card_code',
            DB::raw('CASE WHEN mobile_services.wholesale_or_retail = "wh" THEN wholesale_packages.wholesale_pakage_name ELSE retail_packages.retail_pakage_name END AS package_name'),
            //site name
            'resellers.reseller_name',
            'resellers.reseller_billing_account_no',
            //price
            //customer name
            //customer acc no
            //retail price
            'reseller_mobile_change_logs.what_change',
            //'mobile_services.anniversary_date AS anniversary_date',
        )
            ->leftJoin('reseller_mobile_change_logs', 'reseller_mobile_change_logs.mobile_service_id', '=', 'mobile_services.mobile_service_id')
            ->leftJoin('wholesale_packages', 'wholesale_packages.id', '=', 'mobile_services.wholesale_package_id')
            ->leftJoin('retail_packages', 'retail_packages.id', '=', 'mobile_services.retail_package_id')
            ->leftJoin('simcards', 'simcards.mobile_number', '=', 'mobile_services.mobile_number')
            ->leftJoin('resellers', 'resellers.reseller_id', '=', 'mobile_services.reseller_id');


        if ($User->tenant_role === 'admin') {
            if (!empty($data['reseller_name'])) {
                $resellerId = $data['reseller_name'];
                $simCards = $sim_cards->where('resellers.reseller_id', '=', $resellerId)->get()->toArray();
                $allocated_sim_by_reseller = $allocated_sim_by_resellers->where('resellers.reseller_id', '=', $resellerId)->get()->toArray();
                $unallocated_sim_by_reseller = $unallocated_sim_by_resellers->where('resellers.reseller_id', '=', $resellerId)->get()->toArray();
                $service_listing_repo_by_reseller = $service_listing->where('resellers.reseller_id', '=', $resellerId)->get()->toArray();
                $mobile_service = $mobile_services->where('resellers.reseller_id', '=', $resellerId)->get()->toArray();
                $mobile_update = $mobile_updates->where('resellers.reseller_id', '=', $resellerId)->get()->toArray();
            } else {
                $simCards = $sim_cards->get()->toArray();
                $allocated_sim_by_reseller = $allocated_sim_by_resellers->get()->toArray();
                $unallocated_sim_by_reseller = $unallocated_sim_by_resellers->get()->toArray();
                $service_listing_repo_by_reseller = $service_listing->get()->toArray();
                $mobile_service = $mobile_services->get()->toArray();
                $mobile_update = $mobile_updates->get()->toArray();
            }

        } else {
            $resellerId = Auth::id();

            $simCards = $sim_cards->where('resellers.reseller_id', '=', $resellerId)->get()->toArray();
            $allocated_sim_by_reseller = $allocated_sim_by_resellers->where('resellers.reseller_id', '=', $resellerId)->get()->toArray();
            $unallocated_sim_by_reseller = $unallocated_sim_by_resellers->where('resellers.reseller_id', '=', $resellerId)->get()->toArray();
            $service_listing_repo_by_reseller = $service_listing->where('resellers.reseller_id', '=', $resellerId)->get()->toArray();
            $mobile_service = $mobile_services->where('resellers.reseller_id', '=', $resellerId)->get()->toArray();
            $mobile_update = $mobile_updates->where('resellers.reseller_id', '=', $resellerId)->get()->toArray();
        }

        $result = [
            'simCards' => $simCards,
            'allocated_sim_by_reseller' => $allocated_sim_by_reseller,
            'unallocated_sim_by_reseller' => $unallocated_sim_by_reseller,
            'mobile_service' => $mobile_service,
            'service_listing_repo_by_reseller' => $service_listing_repo_by_reseller,
            'mobile_update' => $mobile_update
        ];
        return $result;

    }

    public function render() {
        return view('livewire.reports.report-generation');
    }
}
