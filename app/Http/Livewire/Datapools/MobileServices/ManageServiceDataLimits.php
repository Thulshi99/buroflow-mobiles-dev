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
use Filament\Tables\Actions\EditAction;
use GuzzleHttp\Client;
use throwable;
use Filament\Notifications\Notification;

class ManageServiceDataLimits extends Component implements HasTable,HasForms
{
    use InteractsWithTable;
    use InteractsWithForms;

    public $tenant_id;

    public $datapool_id;
    public $destination_datapool_id;

    public function render()
    {
        return view('livewire.datapools.mobile-services.manage-service-data-limits');
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
            TextColumn::make('status_in_datapool')->label('Status')
                ->extraAttributes(['class' => 'p-px text-sm'])
                ->default('N/A')
                ->searchable()
                ->sortable(),
            TextColumn::make('status_in_datapool')->label('Status')->enum([
                0 => 'Opt-In Pending',
                1 => 'Opted-In',
                2 => 'Opt-Out Pending',
                3 => 'Opted-Out'
            ]),
            TextColumn::make('data_limit')->label('Data Limit(GB)')
            ->getStateUsing(function (MobileService $record) {
                $data_limit_GB = $record->data_limit / pow(1024, 3);
                $data_limit_GB = number_format($data_limit_GB, 2, '.', '');

                return $data_limit_GB;
            })
            ->extraAttributes(['class' => 'p-px text-sm'])
            ->default('N/A')
            ->searchable()
            ->sortable()

        ];
    }

    protected function getTableActions(): array
    {
        return [
            EditAction::make('set_data_limit')
            ->label('Update Data Limit')
            ->color('success')
            ->icon('heroicon-o-pencil')
            ->successNotificationTitle('Data Limit Updated')
            ->action(function (MobileService $record, array $data): void {

                // try
                // {
                    //Limit In Bytes
                    $updated_data_limit_KB = $data['dataLimit']*1024*1024*1024;

                    $response = $this->updateDataLimitApiCall($record,$updated_data_limit_KB);

                    if($response != null)
                    {
                        if($response['errorCode'] === 0)
                        {
                            if ($response['orderDetails'][0]['success'])
                            {
                                $order_id = $response['orderDetails'][0]['orderId'];

                                $record->update(['data_limit' => $updated_data_limit_KB,
                                                 'notes' => $order_id]);

                                Notification::make()
                                ->title('Data Limit Update Request Has Been Sent SuccessFully')
                                ->success()
                                // ->actions([
                                //     Action::make('Ok')
                                //         ->button()
                                //         ->url(route('datapools.index'), shouldOpenInNewTab: false)
                                //     ])
                                ->persistent()
                                ->send();
                            }
                            else
                            {
                                Notification::make()
                                // ->title('An Error Has Been Occurred While Trying To Update Data Limit')
                                ->title('An Error Has Been Occurred: API Call Returns Unsuccessful Order Details')
                                ->danger()
                                // ->actions([
                                //     Action::make('Ok')
                                //         ->button()
                                //         ->url(route('datapools.show',$this->datapool_id), shouldOpenInNewTab: false)
                                //     ])
                                ->persistent()
                                ->send();
                            }
                        }
                        else
                        {
                            Notification::make()
                            // ->title('An Error Has Been Occurred While Trying To Update Data Limit')
                            ->title('An Error Has Been Occurred: API Call Returns An Error')
                            ->danger()
                            // ->actions([
                            //     Action::make('Ok')
                            //         ->button()
                            //         ->url(route('datapools.show',$this->datapool_id), shouldOpenInNewTab: false)
                            //     ])
                            ->persistent()
                            ->send();
                        }
                    }
                    else
                    {
                        Notification::make()
                        // ->title('An Error Has Been Occurred While Trying To Update Data Limit')
                        ->title('Response is Null')
                        ->danger()
                        // ->actions([
                        //     Action::make('Ok')
                        //         ->button()
                        //         ->url(route('datapools.show',$this->datapool_id), shouldOpenInNewTab: false)
                        //     ])
                        ->persistent()
                        ->send();
                    }

                // }
                // catch(Throwable $e)
                // {
                //     Notification::make()
                //     // ->title('An Error Has Been Occurred While Trying To Update Data Limit')
                //     ->title('Exception Has Been Occurred')
                //     ->danger()
                //     // ->actions([
                //     //     Action::make('Ok')
                //     //         ->button()
                //     //         ->url(route('datapools.show',$this->datapool_id), shouldOpenInNewTab: false)
                //     //     ])
                //     ->persistent()
                //     ->send();
                // }

            })
            ->form([
                Forms\Components\TextInput::make('dataLimit')
                    ->label('Update Data Limit')
                    ->required(),
            ])
            ->requiresConfirmation()
            ->modalHeading('Update Data Limit')
            ->modalSubheading('Are you sure you\'d like to update data limit?')
            ->modalButton('Update')
        ];
    }

    public function updateDataLimitApiCall($service,$data_limit)
    {
        try
        {
            $username   = env('BENZINE_USERNAME');
            $password   = env('BENZINE_PASSWORD');
            $cust_no    = env('OCTANE_DEFAULT_CUSTNO','382422');
            $lineseq_no = $service->lineSeqNo;

            $client = new Client();

            $apiUrl = 'https://benzine.telcoinabox.com/tiab/api/v1/datapool/modify-consumer-data-limit';

            $requestData = [
                'consumerServices' => [
                    [
                        'custNo' => $cust_no,
                        'lineSeqNo' => $lineseq_no,
                        'dataLimit' => $data_limit
                    ]
                ]
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
        }
        catch(Throwable $e)
        {
            $data = null;
        }

        return $data;
    }

}

