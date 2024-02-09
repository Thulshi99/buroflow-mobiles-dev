<?php

namespace App\Http\Livewire\Datapools\MobileServices;

use Livewire\Component;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Actions\Action;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Forms\Concerns\InteractsWithForms;
use Illuminate\Database\Eloquent\Relations\Relation;
use App\Models;
use App\Models\DataPool;
use Filament\Forms;
use App\Models\MobileService;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Session;
use GuzzleHttp\Client;
use App\Http\Requests\RemoveServicesFromPoolRequest;
use Artisaninweb\SoapWrapper\SoapWrapper;
use throwable;
use Filament\Notifications\Notification;

class ListDataPoolServices extends Component implements HasTable,HasForms
{
    use InteractsWithTable;
    use InteractsWithForms;

    public $tenant_id;

    public $datapool_id;
    public $destination_datapool_id;

    protected $soapWrapper;

    public function render()
    {
        return view('livewire.datapools.mobile-services.list-data-pool-services');
    }

    public function mount($datapool_id): void
    {
        if (tenant()) {
            $this->tenant_id = tenant()->id;
        }

        // Use the $datapool_id parameter to perform actions or retrieve data pool
        $this->datapool_id = $datapool_id;
    }

    protected function getTableQuery(): Builder|Relation
    {
        if (tenant()) {
            $this->tenant_id = tenant()->id;
        }

        $tenant = $this->tenant_id;
        tenancy()->initialize($tenant);

        $mobile_services_in_pool = MobileService::where('datapool_id', $this->datapool_id)
                                                    ->orderBy('created_at', 'DESC');

        return $mobile_services_in_pool;
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('mobile_number')->label('Service Number')
                ->extraAttributes(['class' => 'p-px text-sm'])
                ->default('N/A')
                ->sortable()
                ->searchable(),
            TextColumn::make('retail_package_id')->label('Plan Name')
                ->extraAttributes(['class' => 'p-px text-sm'])
                ->searchable()
                ->sortable()
                ->default('N/A'),
            TextColumn::make('data_limit')->label('Data Limit(GB)')
                ->getStateUsing(function (MobileService $record) {
                    $data_limit_GB = $record->data_limit / pow(1024, 3);
                    $data_limit_GB = number_format($data_limit_GB, 2, '.', '');

                    return $data_limit_GB;
                })
                ->extraAttributes(['class' => 'p-px text-sm'])
                ->default('N/A')
                ->searchable()
                ->sortable(),
            TextColumn::make('status_in_datapool')->label('Status')->enum([
                0 => 'Opt-In Pending',
                1 => 'Opted-In',
                2 => 'Opt-Out Pending',
                3 => 'Opted-Out'
            ])

        ];
    }

    protected function getTableActions(): array
    {
        return [
            Action::make('remove_service')
            ->label('Remove')
            ->color('danger')
            ->icon('heroicon-o-trash')
//            ->action(function (MobileService $record): void {
/*                try
                {
                    $data = [
                        'username' => env('OCTANE_USERNAME'),
                        'password' => env('OCTANE_PASSWORD'),
                        'cust_no' => $record->order_id,
                        'service_number' => $record->mobile_number
                    ];

                    $request = new RemoveServicesFromPoolRequest($data);

                    $endpoint = env('OCTANE_BASE_URL','https://benzine.telcoinabox.com/tiab')."/UtbOrder?wsdl";

                    $this->soapWrapper = new SoapWrapper();

                    $this->soapWrapper_msn->add('UtbOrderPortBinding', function ($order) use ($endpoint){
                        $order
                            ->wsdl($endpoint) // The WSDL endpoint
                            ->trace(true);  // Optional: (parameter: true/false)
                    });

                    $response = $this->soapWrapper->call('UtbOrderPortBinding.orderCreateBulk', [
                        new \SoapVar($request->getXmlBody(), XSD_ANYXML)
                    ]);

                    if($response != null)
                    {
                        //Get OrderID and Error Code
                        $errorCode = $response->ordersCreateResponse->orderResponse->errorCode;

                        if($errorCode == 0)
                        {
                            $order_id = $response->ordersCreateResponse->orderResponse->orderId;

                            $record->update(['datapool_id' => NULL,
                                            'note' => $order_id,
                                            'status_in_datapool' => 0]);

                            Notification::make()
                            ->title('Remove Services Request Has Been Sent Successfully')
                            ->success()
                            ->body('It will take around 15 minutes to remove services from the pool completely.')
                            ->actions([
                                \Filament\Notifications\Actions\Action::make('Ok')
                                    ->button()
                                    ->url(route('datapools.show',$this->datapool_id), shouldOpenInNewTab: false)
                                ])
                            ->persistent()
                            ->send();
                        }
                        else
                        {
                            Notification::make()
                            ->title('Error Ocurred While Removing Services From The Data Pool')
                            ->danger()
                            ->actions([
                                \Filament\Notifications\Actions\Action::make('Ok')
                                    ->button()
                                    ->url(route('datapools.show',$this->datapool_id), shouldOpenInNewTab: false)
                                ])
                            ->persistent()
                            ->send();
                        }
                    }
                    else
                    {
                        Notification::make()
                        ->title('Error Ocurred While Removing Services From The Data Pool')
                        ->danger()
                        ->actions([
                            \Filament\Notifications\Actions\Action::make('Ok')
                                ->button()
                                ->url(route('datapools.show',$this->datapool_id), shouldOpenInNewTab: false)
                            ])
                        ->persistent()
                        ->send();
                    }
                }
                catch(Throwable $e)
                {
                    Notification::make()
                    ->title('Error Ocurred While Removing Services From The Data Pool')
                    ->danger()
                    ->actions([
                        \Filament\Notifications\Actions\Action::make('Ok')
                            ->button()
                            ->url(route('datapools.show',$this->datapool_id), shouldOpenInNewTab: false)
                        ])
                    ->persistent()
                    ->send();
                }
            })
*/
                //Usual DB Operation Without API Call
            //     $record->update(['datapool_id' => NULL]);


            // })
            ->requiresConfirmation()
            ->modalHeading('Remove Service')
            ->modalSubheading('Are you sure you\'d like to remove this service from this pool?')
            ->modalButton('Yes')
            ,
            Action::make('transfer_service')
            ->label('Transfer')
            ->color('info')
            ->icon('heroicon-o-arrow-left')
            ->successNotificationTitle('Service Transferred Successfully From The Pool')
            ->action(function (MobileService $record,array $data): void {
                // try
                // {
                    //Find Current Datapool
                    $dataPool = DataPool::find($this->datapool_id);

                    //Find Destination Datapool
                    $this->destination_datapool_id = $data['dataPoolID'];
                    $destination_datapool = Datapool::find($this->destination_datapool_id);

                    //Add Service Number to an Array
                    $service_nos = [$record->mobile_number];

                    //Call API
                    $response = $this->transferApiCall($dataPool,$service_nos,$destination_datapool);

                    if($response != null)
                    {
                        if(isset($response['orderDetails']))
                        {
                            if($response['orderDetails'][0]['success'] == "true")
                            {
                                $order_id = $response['orderDetails'][0]['orderId'];

                                $record->update(['datapool_id' => $this->destination_datapool_id,
                                                'notes' => $order_id,
                                                'status_in_datapool' => 0]);

                                Notification::make()
                                ->title('Service Transfer Request Has Been Sent Successfully')
                                ->success()
                                ->body('It will take around 15 minutes to complete the transfer.')
                                ->actions([
                                    \Filament\Notifications\Actions\Action::make('Ok')
                                        ->button()
                                        ->url(route('datapools.show',$this->datapool_id), shouldOpenInNewTab: false)
                                    ])
                                ->persistent()
                                ->send();
                            }

                        }
                        else
                        {
                            Notification::make()
                            ->title('An Error Has Been Occurred While Trying To Transfer The Service')
                            ->danger()
                            ->actions([
                                \Filament\Notifications\Actions\Action::make('Ok')
                                    ->button()
                                    ->url(route('datapools.show',$this->datapool_id), shouldOpenInNewTab: false)
                                ])
                            ->persistent()
                            ->send();

                        }
                    }
                    else
                    {
                        Notification::make()
                        ->title('An Error Has Been Occurred While Trying To Transfer The Service')
                        ->danger()
                        ->actions([
                            \Filament\Notifications\Actions\Action::make('Ok')
                                ->button()
                                ->url(route('datapools.show',$this->datapool_id), shouldOpenInNewTab: false)
                            ])
                        ->persistent()
                        ->send();

                    }
                // }
                // catch(Throwable $e)
                // {
                //     Notification::make()
                //     ->title('An Error Has Been Occurred While Trying To Transfer The Service')
                //     ->danger()
                //     ->actions([
                //         \Filament\Notifications\Actions\Action::make('Ok')
                //             ->button()
                //             ->url(route('datapools.show',$this->datapool_id), shouldOpenInNewTab: false)
                //         ])
                //     ->persistent()
                //     ->send();
                // }

            })
            ->form([
                Forms\Components\Select::make('dataPoolID')
                    ->label('Destination Data Pool')
                    ->options(DataPool::query()->whereNot('datapool_id', 0)->whereNot('status', 3)->pluck('description', 'id'))
                    ->required(),
            ])
        ];
    }

    protected function getTableBulkActions(): array
    {
        return [
            BulkAction::make('Remove Services')
                ->deselectRecordsAfterCompletion()
                ->requiresConfirmation()
                ->color('danger')
                ->icon('heroicon-o-trash')
//                ->action(function (Collection $records) :void{
/*
                        //SOAP API Call
                        $records->each(function ($record) {
                            try
                            {
                                $data = [
                                    'username' => env('OCTANE_USERNAME'),
                                    'password' => env('OCTANE_PASSWORD'),
                                    'cust_no' => $record->order_id,
                                    'service_number' => $record->mobile_number
                                ];

                                $request = new RemoveServicesFromPoolRequest($data);

                                $endpoint = env('OCTANE_BASE_URL','https://benzine.telcoinabox.com/tiab')."/UtbOrder?wsdl";

                                $this->soapWrapper = new SoapWrapper();

                                $this->soapWrapper_msn->add('UtbOrderPortBinding', function ($order) use ($endpoint){
                                    $order
                                        ->wsdl($endpoint) // The WSDL endpoint
                                        ->trace(true);  // Optional: (parameter: true/false)
                                });

                                $response = $this->soapWrapper->call('UtbOrderPortBinding.orderCreateBulk', [
                                    new \SoapVar($request->getXmlBody(), XSD_ANYXML)
                                ]);

                                if($response != null)
                                {
                                    //Get OrderID and Error Code
                                    $errorCode = $response->ordersCreateResponse->orderResponse->errorCode;

                                    if($errorCode == 0)
                                    {
                                        $order_id = $response->ordersCreateResponse->orderResponse->orderId;

                                        $record->update(['datapool_id' => NULL,
                                                        'note' => $order_id,
                                                        'status_in_datapool' => 0]);

                                        Notification::make()
                                        ->title('Remove Services Request Has Been Sent Successfully')
                                        ->success()
                                        ->body('It will take around 15 minutes to remove services from the pool completely.')
                                        ->actions([
                                            \Filament\Notifications\Actions\Action::make('Ok')
                                                ->button()
                                                ->url(route('datapools.show',$this->datapool_id), shouldOpenInNewTab: false)
                                            ])
                                        ->persistent()
                                        ->send();
                                    }
                                    else
                                    {
                                        Notification::make()
                                        ->title('Error Ocurred While Removing Services From The Data Pool')
                                        ->danger()
                                        ->actions([
                                            \Filament\Notifications\Actions\Action::make('Ok')
                                                ->button()
                                                ->url(route('datapools.show',$this->datapool_id), shouldOpenInNewTab: false)
                                            ])
                                        ->persistent()
                                        ->send();
                                    }
                                }
                                else
                                {
                                    Notification::make()
                                    ->title('Error Ocurred While Removing Services From The Data Pool')
                                    ->danger()
                                    ->actions([
                                        \Filament\Notifications\Actions\Action::make('Ok')
                                            ->button()
                                            ->url(route('datapools.show',$this->datapool_id), shouldOpenInNewTab: false)
                                        ])
                                    ->persistent()
                                    ->send();
                                }


                            }
                            catch(Throwable $e)
                            {
                                Notification::make()
                                ->title('Error Ocurred While Removing Services From The Data Pool')
                                ->danger()
                                ->actions([
                                    \Filament\Notifications\Actions\Action::make('Ok')
                                        ->button()
                                        ->url(route('datapools.show',$this->datapool_id), shouldOpenInNewTab: false)
                                    ])
                                ->persistent()
                                ->send();
                            }

                        });
*/
                //Usual DB Operation Without API Call
                // $records->each(function ($record) {

                //         $record->update(['datapool_id' => NULL]);

                //     });

                // })
                // ->requiresConfirmation()
                // ->modalHeading('Remove Services')
                // ->modalSubheading('Are you sure you\'d like to remove these services from this pool?')
                // ->modalButton('Yes')
                ,
                BulkAction::make('Transfer Services')
                ->icon('heroicon-o-arrow-left')
                ->action(function (Collection $records, array $data): void {
                    // try
                    // {
                        //Find Current Datapool
                        $dataPool = DataPool::find($this->datapool_id);

                        //Find Destination Datapool
                        $this->destination_datapool_id = $data['dataPoolID'];
                        $destination_datapool = Datapool::find($this->destination_datapool_id);

                        // Collect mobile numbers
                        $service_numbers = $records->map(function ($record) {
                            return $record->mobile_number;
                        })->toArray();

                        // Call API
                        $response = $this->transferApiCall($dataPool, $service_numbers, $destination_datapool);

                        if($response != null)
                        {
                            if(isset($response['orderDetails']))
                            {
                                if($response['orderDetails'][0]['success'] == "true")
                                {
                                    $order_id = $response['orderDetails'][0]['orderId'];

                                    // Update datapool_id in the database
                                    $records->each(function ($record) use ($order_id){
                                        $record->update(['datapool_id' => $this->destination_datapool_id,
                                                        'notes' => $order_id,
                                                        'status_in_datapool' => 0]);
                                    });

                                    Notification::make()
                                    ->title('Services Transfer Request Has Been Sent Successfully')
                                    ->success()
                                    ->body('It will take around 15 minutes to complete the transfer.')
                                    ->actions([
                                        \Filament\Notifications\Actions\Action::make('Ok')
                                            ->button()
                                            ->url(route('datapools.show',$this->datapool_id), shouldOpenInNewTab: false)
                                        ])
                                    ->persistent()
                                    ->send();
                                }

                            }
                            else
                            {
                                Notification::make()
                                ->title('An Error Has Been Occurred While Trying To Transfer Services')
                                ->danger()
                                ->actions([
                                    \Filament\Notifications\Actions\Action::make('Ok')
                                        ->button()
                                        ->url(route('datapools.show',$this->datapool_id), shouldOpenInNewTab: false)
                                    ])
                                ->persistent()
                                ->send();

                            }
                        }
                        else
                        {
                            Notification::make()
                            ->title('An Error Has Been Occurred While Trying To Transfer Services')
                            ->danger()
                            ->actions([
                                \Filament\Notifications\Actions\Action::make('Ok')
                                    ->button()
                                    ->url(route('datapools.show',$this->datapool_id), shouldOpenInNewTab: false)
                                ])
                            ->persistent()
                            ->send();

                        }
                    // }
                    // catch(Throwable $e)
                    // {
                    //     Notification::make()
                    //     ->title('An Error Has Been Occurred While Trying To Transfer Services')
                    //     ->danger()
                    //     ->actions([
                    //         \Filament\Notifications\Actions\Action::make('Ok')
                    //             ->button()
                    //             ->url(route('datapools.show',$this->datapool_id), shouldOpenInNewTab: false)
                    //         ])
                    //     ->persistent()
                    //     ->send();
                    // }

                })
                ->form([
                    Forms\Components\Select::make('dataPoolID')
                        ->label('Destination Data Pool')
                        ->options(DataPool::query()->whereNot('datapool_id', 0)->whereNot('status', 3)->pluck('description', 'id'))
                        ->required(),
                ])
        ];
    }


    public function transferApiCall($current_dataPool,$service_numbers,$new_datapool)
    {
        $data = null;

        // try
        // {
            $username                = env('OCTANE_USERNAME');
            $password                = env('OCTANE_PASSWORD');
            $cust_no                 = env('OCTANE_DEFAULT_CUSTNO','382422');
            $current_lineseq_no      = $current_dataPool->lineseq_no;
            $new_datapool_id         = $new_datapool->datapool_id;
            $new_lineseq_no          = $new_datapool->lineseq_no;
            $service_numbers         = $service_numbers;

            $client = new Client();

            $apiUrl = 'https://benzine.telcoinabox.com/tiab/api/v1/datapool/transfer';

            $requestData = [
                'custno'               => $cust_no,
                'lineseqno'            => $current_lineseq_no,
                'newProviderId'        => $new_datapool_id,
                'newProviderLineseqno' => $new_lineseq_no,
                'serviceNumbers'       => $service_numbers
            ];

            $response = $client->post($apiUrl, [
                'json' => $requestData,
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'auth' => [$username, $password],
            ]);


            if ($response->getStatusCode() == 200)
            {
                $responseData = $response->getBody()->getContents();
                $data = json_decode($responseData, true);

                if($data != null)
                {
                    return $data;
                }
            }
        //}
        // catch(Throwable $e)
        // {
        //     $data = null;
        // }

        return $data;
    }
}

