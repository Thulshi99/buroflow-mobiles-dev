<?php

namespace App\Http\Livewire\Datapools;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use App\Models\DataPool;
use App\Models\MobileService;
use Illuminate\Support\HtmlString;
use Livewire\Component;
use App\Http\Requests\GetServiceDataConsumptionDetailsRequest;
use Artisaninweb\SoapWrapper\SoapWrapper;
use throwable;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action;

class DataConsumption extends Component implements HasTable
{
    use InteractsWithTable;

    public $tenant_id;

    public $datapool_id;

    protected $soapWrapper;

    public function render()
    {
        return view('livewire.datapools.data-consumption');
    }

    public function mount($datapool_id): void
    {
        if (tenant()) {
            $this->tenant_id = tenant()->id;
        }

        // Use the $datapool_id parameter to perform actions or retrieve data pool
        $this->datapool_id = $datapool_id;
    }

    protected $queryString = [
        'tableFilters',
        'tableSortColumn',
        'tableSortDirection',
        'tableSearchQuery' => ['except' => ''],
    ];

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
            TextColumn::make('mobile_service_id')->label('Service Number')
                ->extraAttributes(['class' => 'p-px text-sm'])
                ->default('N/A')
                ->sortable()
                ->searchable(),
            TextColumn::make('data_used')->label('Used(GB)')
            ->getStateUsing(function (MobileService $record) {
                // try
                // {
                    // SOAP API Request
                    $data = [
                        'username' => env('OCTANE_USERNAME'),
                        'password' => env('OCTANE_PASSWORD'),
                        'cust_no' => env('OCTANE_DEFAULT_CUSTNO','382422'),
                        'lineSeq_no' => $record->lineSeqNo
                    ];

                    $request = new GetServiceDataConsumptionDetailsRequest($data);

                    $endpoint = env('OCTANE_BASE_URL','https://benzine.telcoinabox.com/tiab')."/UtbMobile?wsdl";

                    $this->soapWrapper = new SoapWrapper();

                    $this->soapWrapper->add('UtbMobilePortBinding', function ($order) use ($endpoint){
                        $order
                            ->wsdl($endpoint) // The WSDL endpoint
                            ->trace(true);  // Optional: (parameter: true/false)
                    });

                    $response = $this->soapWrapper->call('UtbMobilePortBinding.queryBalanceV3', [
                        new \SoapVar($request->getXmlBody(), XSD_ANYXML)
                    ]);

                    if ($response != null)
                    {
                        //Get error code
                        $errorCode = $response->return->errorCode;

                        //Get used_data and data_quota if there is no error
                        if( $errorCode === 0)
                        {
                            //total data quota
                            $data_quota = 0;

                            //total usage
                            $data_used = 0;

                            $balances = $response->return->balances;

                            // An array to store data balances
                            $dataBalances = [];

                            foreach ($balances->balance as $balance) {
                                if ((string) $balance->type === 'DATA') {
                                    $dataBalances[] = [
                                        'type' => (string) $balance->type,
                                        'allowanceFmt' => (int) $balance->allowanceFmt,
                                        'usedFmt' => (int) $balance->usedFmt,
                                    ];

                                    $data_quota = $data_quota + (int) $balance->allowanceFmt;
                                    $data_used = $data_used + (int) $balance->usedFmt;
                                }
                            }

                            $record->update(['data_used' => $data_used,
                                            'data_quota' => $data_quota]);


                        }
                        else
                        {
                            $data_quota = 'N/A';
                            $data_used  = 'N/A';

                            $record->update(['data_used' => -1,
                                            'data_quota' => -1]);
                        }

                    }
                    else
                    {
                        $data_quota = 'N/A';
                        $data_used  = 'N/A';

                        $record->update(['data_used' => -1,
                                        'data_quota' => -1]);
                    }

                    if($data_used != 'N/A')
                    {
                        $data_used = $data_used / pow(1024, 3);
                        $data_used = number_format($data_used, 2, '.', '');

                        return $data_used;
                    }
                    else
                    {
                        return $data_used;
                    }
                // }
                // catch(Throwable $e)
                // {
                //     $data_quota = 'N/A';
                //     $data_used  = 'N/A';

                //     $record->update(['data_used' => -1,
                //                     'data_quota' => -1]);

                //     return $data_used;
                // }

            })
                ->extraAttributes(['class' => 'p-px text-sm'])
                ->default('N/A')
                ->searchable()
                ->sortable(),
            TextColumn::make('data_quota')->label('Quota(GB)')
                ->getStateUsing(function (MobileService $record) {
                    $data_quota = $record->data_quota;

                    if($data_quota != -1)
                    {
                        $data_quota = $data_quota / pow(1024, 3);
                        $data_quota = number_format($data_quota, 2, '.', '');

                        return $data_quota;
                    }
                    else
                    {
                        $data_quota = 'N/A';

                        return $data_quota;
                    }
                })
                ->extraAttributes(['class' => 'p-px text-sm'])
                ->default('N/A')
                ->searchable()
                ->sortable(),
            TextColumn::make('Usage')
            ->getStateUsing(function (MobileService $record) {
                $data_quota = $record->data_quota;
                $data_used  = $record->data_used;

                if($data_quota != -1 && $data_used != -1)
                {
                    if ($data_quota > 0) {
                        $usagePercentage = number_format(($data_used / $data_quota) * 100, 2);
                    } else {
                        $usagePercentage = 0.00;
                    }

                    // Determine the color based on $usagePercentage
                    if ($usagePercentage > 130) {
                        $color = '#7B1818';
                    } elseif ($usagePercentage > 100) {
                        $color = 'red';
                    } elseif ($usagePercentage > 85) {
                        $color = 'orange';
                    } else {
                        $color = 'green';
                    }

                    return new HtmlString(html: '
                    <div style="width: 300px; height: 20px; background-color: #f0f0f0; border-radius: 4px; overflow: hidden;">
                        <div style="width:' . $usagePercentage . '%; height: 100%; background-color: ' . $color . '; text-align: center; line-height: 20px; color: #fff;">
                            ' . $usagePercentage . '%
                        </div>
                    </div>'
                    );
                }

            })->label('Usage')
                ->extraAttributes(['class' => 'p-px text-sm']),

        ];
    }
}
