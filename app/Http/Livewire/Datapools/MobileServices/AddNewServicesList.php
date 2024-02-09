<?php

namespace App\Http\Livewire\Datapools\MobileServices;

use Livewire\Component;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Database\Eloquent\Collection;
use Filament\Tables\Concerns\InteractsWithTable;
use Illuminate\Database\Eloquent\Relations\Relation;
use App\Models;
use App\Models\MobileService;
use App\Models\DataPool;
use Filament\Tables\Columns\CheckboxColumn;
use App\Http\Requests\AddServicesToPoolRequest;
use Artisaninweb\SoapWrapper\SoapWrapper;
use throwable;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action;

class AddNewServicesList extends Component implements HasTable
{
    use InteractsWithTable;

    public $tenant_id;
    public $datapool_id;

    protected $soapWrapper;


    public function mount($datapool_id): void
    {
        if (tenant()) {
            $this->tenant_id = tenant()->id;
        }

        // Use the $datapool_id parameter to perform actions or retrieve data pool
        $this->datapool_id = $datapool_id;
    }

    public function render()
    {
        return view('livewire.datapools.mobile-services.add-new-services-list');
    }

    protected function getTableQuery(): Builder|Relation
    {
        if (tenant()) {
            $this->tenant_id = tenant()->id;
        }

        $tenant = $this->tenant_id;
        tenancy()->initialize($tenant);

        $mobile_services_to_be_added = MobileService::whereNull('datapool_id')
                                                    ->orderBy('created_at', 'DESC');

        return $mobile_services_to_be_added;
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
            TextColumn::make('data_used')->label('Quota(GB)')
            ->extraAttributes(['class' => 'p-px text-sm'])
            ->default('N/A')
            ->searchable()
            ->sortable()

        ];
    }

    protected function getTableBulkActions(): array
    {
        return [
            BulkAction::make('Add To Pool')
                ->deselectRecordsAfterCompletion()
                ->action(function (Collection $records) {
                    $datapool_id = $this->datapool_id;
                    $records->each(function ($record) use ($datapool_id) {
                        // try
                        // {
                            $datapool = DataPool::find($datapool_id);

                            // SOAP API Request - Add Consumers to Pool
                            $data = [
                                'username' => env('OCTANE_USERNAME'),
                                'password' => env('OCTANE_PASSWORD'),
                                'cust_no' => env('OCTANE_DEFAULT_CUSTNO','382422'),
                                'datapool_id' => $datapool->datapool_id,
                                'service_no' => $record->mobile_number,
                            ];

                            $request = new AddServicesToPoolRequest($data);

                            $endpoint = env('OCTANE_BASE_URL','https://benzine.telcoinabox.com/tiab')."/UtbOrder?wsdl";

                            $this->soapWrapper = new SoapWrapper();

                            $this->soapWrapper->add('UtbOrderPortBinding', function ($order) use ($endpoint){
                                $order
                                    ->wsdl($endpoint) // The WSDL endpoint
                                    ->trace(true);  // Optional: (parameter: true/false)
                            });

                            $response = $this->soapWrapper->call('UtbOrderPortBinding.orderCreateBulk', [
                                new \SoapVar($request->getXmlBody(), XSD_ANYXML)
                            ]);


                            if ($response != null)
                            {
                                //Get OrderID and Error Code
                                $errorCode = $response->ordersCreateResponse->orderResponse->errorCode;

                                  if($errorCode == 0)
                                {
                                    $order_id = $response->ordersCreateResponse->orderResponse->orderId;

                                    $record->update(['datapool_id' => $datapool_id,
                                                    'note' => $order_id,
                                                    'status_in_datapool' => 0]);


                                    Notification::make()
                                    ->title('Add Services Request Has Been Sent Successfully')
                                    ->success()
                                    ->body('It will take around 15 minutes to add services into the pool completely.')
                                    ->actions([
                                        Action::make('Ok')
                                            ->button()
                                            ->url(route('datapools.show',$this->datapool_id), shouldOpenInNewTab: false)
                                        ])
                                    ->persistent()
                                    ->send();

                                    //return redirect()->route('datapools.show', ['datapool' =>$datapool_id])->with('success', 'Services Have Been Added to Pool Successfully');
                                }
                                else
                                {
                                    Notification::make()
                                    ->title('Error Ocurred While Adding Services To The Data Pool')
                                    ->danger()
                                    ->actions([
                                        Action::make('Ok')
                                            ->button()
                                            ->url(route('datapools.manage.add',$this->datapool_id), shouldOpenInNewTab: false)
                                        ])
                                    ->persistent()
                                    ->send();
                                }
                            }
                            else
                            {
                                Notification::make()
                                ->title('Error Ocurred While Adding Services To The Data Pool')
                                ->danger()
                                ->actions([
                                    Action::make('Ok')
                                        ->button()
                                        ->url(route('datapools.manage.add',$this->datapool_id), shouldOpenInNewTab: false)
                                    ])
                                ->persistent()
                                ->send();
                            }

                        // }
                        // catch(Throwable $e)
                        // {
                        //     Notification::make()
                        //     ->title('Error Ocurred While Adding Services To The Data Pool')
                        //     ->danger()
                        //     ->actions([
                        //         Action::make('Ok')
                        //             ->button()
                        //             ->url(route('datapools.manage.add',$this->datapool_id), shouldOpenInNewTab: false)
                        //         ])
                        //     ->persistent()
                        //     ->send();
                        // }

                    });

                }),
        ];
    }

}

