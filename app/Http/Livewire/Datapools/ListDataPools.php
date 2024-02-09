<?php

namespace App\Http\Livewire\Datapools;

use Livewire\Component;
use Illuminate\Support\Arr;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TagsColumn;
use Filament\Tables\Columns\BadgeColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Actions\Action;
use Filament\Tables\Concerns\InteractsWithTable;
use Illuminate\Database\Eloquent\Relations\Relation;
use App\Models\DataPool;
use App\Models\MobileService;
use App\Http\Requests\OrderQueryRequest;
use App\Http\Requests\GetServiceDetailRequest;
use Artisaninweb\SoapWrapper\SoapWrapper;
use App\Models\Tenant;
use Illuminate\Support\Facades\Http;
use Throwable;
use Filament\Notifications\Notification;

class ListDataPools extends Component implements HasTable
{
    use InteractsWithTable;

    public $tenant_id;

    protected $soapWrapper_msn;
    protected $soapWrapper_lineseq;

    public function render()
    {
        return view('livewire.datapools.list-data-pools');
    }

    public function mount(): void
    {
        if (tenant()) {
            $this->tenant_id = tenant()->id;
        }
    }

    protected $queryString = [
        'tableFilters',
        'tableSortColumn',
        'tableSortDirection',
        'tableSearchQuery' => ['except' => ''],
    ];

    protected function getTableActions(): array
    {
        return [
            Action::make('manage_pool')
                ->label('Manage Pool')
                ->url(
                    fn (DataPool $record): string => route(
                        'datapools.show',
                        ['datapool' => $record->id]
                    )
                )
                ->color('success')
                ->icon('heroicon-s-pencil'),
            Action::make('data_consumption')
                ->label('Data Consumption')
                ->url(
                    fn (DataPool $record): string => route(
                        'datapools.dataConsumption',
                        ['id' => $record->id]
                    )
                )
                ->color('warning')
                ->icon('carbon-progress-bar')

        ];
    }

    protected function getTableQuery(): Builder|Relation
    {
        if (tenant()) {
            $this->tenant_id = tenant()->id;
        }

        $tenant = $this->tenant_id;
        tenancy()->initialize($tenant);

        $dataPool = DataPool::Query()->whereNot('status',3)->orderBy('created_at', 'DESC');

        return $dataPool;
    }


    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('datapool_id')
            ->getStateUsing(function (DataPool $record) {
                // try
                // {
                    $datapool_id  = $record->datapool_id;

                    if ($datapool_id == 0)
                    {

                        //Fetch MSN
                        $data_msn = [
                            'username' => env('OCTANE_USERNAME'),
                            'password' => env('OCTANE_PASSWORD'),
                            'order_id' => $record->order_id
                        ];

                        $request_msn = new OrderQueryRequest($data_msn);

                        $endpoint = env('OCTANE_BASE_URL','https://benzine.telcoinabox.com/tiab')."/UtbOrder?wsdl";

                        $this->soapWrapper_msn = new SoapWrapper();

                        $this->soapWrapper_msn->add('UtbOrderPortBinding', function ($order) use ($endpoint){
                            $order
                                ->wsdl($endpoint) // The WSDL endpoint
                                ->trace(true);  // Optional: (parameter: true/false)
                        });

                        $response_msn = $this->soapWrapper_msn->call('UtbOrderPortBinding.orderQuery', [
                            new \SoapVar($request_msn->getXmlBody(), XSD_ANYXML)
                        ]);

                        if($response_msn != null)
                        {
                            $errorCode_msn = $response_msn->return->errorCode;

                            if($errorCode_msn == 0)
                            {
                                $msn = $response_msn->return->orderQueryResponse->orderItems->wmeNewReqItem->msn;

                                if($msn != null)
                                {
                                    //Fetch Datapool_ID
                                    $data_lineseq = [
                                        'username' => env('OCTANE_USERNAME'),
                                        'password' => env('OCTANE_PASSWORD'),
                                        'cust_no'  => env('OCTANE_DEFAULT_CUSTNO','382422'),
                                        'msn'      => $msn
                                    ];

                                    $request_lineseq = new GetServiceDetailRequest($data_lineseq);

                                    $endpoint = env('OCTANE_BASE_URL','https://benzine.telcoinabox.com/tiab')."/UtbServiceV3?wsdl";

                                    $this->soapWrapper_lineseq = new SoapWrapper();

                                    $this->soapWrapper_lineseq->add('UtbServiceV3PortBinding', function ($order) use ($endpoint){
                                        $order
                                            ->wsdl($endpoint) // The WSDL endpoint
                                            ->trace(true);  // Optional: (parameter: true/false)
                                    });

                                    $response_lineseq = $this->soapWrapper_lineseq->call('UtbServiceV3PortBinding.getServiceDetail', [
                                        new \SoapVar($request_lineseq->getXmlBody(), XSD_ANYXML)
                                    ]);

                                    if($response_lineseq != null)
                                    {
                                        $errorCode_lineseq = $response_lineseq->return->errorCode;

                                        if($errorCode_lineseq == 0)
                                        {
                                            $lineSeqno = optional($response_lineseq->return)->Instances->Instance->serviceNumberDetail->lineSeqno ?? null;


                                            if($lineSeqno != null)
                                            {

                                                if($this->tenant_id != 'admin')
                                                {
                                                    //duplicate data to Admin
                                                    tenancy()->initialize('admin');
                                                    $record->update([
                                                        "lineseq_no" => $lineSeqno
                                                    ]);
                                                    tenancy()->end();
                                                }

                                                if($this->tenant_id == 'admin')
                                                {
                                                    $current_team_id = 1;
                                                }
                                                else{
                                                    $current_team_id = 0;
                                                }

                                                if ($record)
                                                {
                                                    $tenant_id = $this->tenant_id;
                                                    $tenant = Tenant::find($tenant_id);

                                                    $tenant->run(function () use ($record,$lineSeqno)
                                                    {
                                                        $record->update([
                                                            "lineseq_no" => $lineSeqno
                                                        ]);
                                                    });
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }

                        if($record->lineseq_no != null)
                        {
                            //Fetch Datapool ID
                            $username = env('OCTANE_USERNAME');
                            $password = env('OCTANE_PASSWORD');

                            $response = Http::withBasicAuth($username, $password)
                                        ->get('https://benzine.telcoinabox.com/tiab/api/v1/datapool', [
                                            'custno'    => env('OCTANE_DEFAULT_CUSTNO','382422'),
                                            'lineseqno' => $record->lineseq_no
                                        ]);

                            if ($response->OK()) {
                                $responseData = $response->json();

                                if($responseData['success'] == "true")
                                {
                                    $datapool_id     = $responseData['provider']['memberId'];
                                    $datapool_status = $responseData['provider']['dataPoolStatus'];

                                    switch ($datapool_status) {
                                        case 'OPTED-IN PENDING':
                                            $datapool_status = 0;
                                            break;
                                        case 'OPTED-IN':
                                            $datapool_status = 1;
                                            break;
                                        case 'OPTED-OUT PENDING':
                                            $datapool_status = 2;
                                            break;
                                        case 'OPTED-OUT':
                                            $datapool_status = 3;
                                            break;
                                        default:
                                            $datapool_status = 0;
                                    }

                                    if($datapool_id != null && $datapool_status != null)
                                    {


                                        if($this->tenant_id != 'admin')
                                        {
                                            //duplicate data to Admin
                                            tenancy()->initialize('admin');
                                            $record->update([
                                                "datapool_id" => $datapool_id,
                                                "status"      => $datapool_status
                                            ]);
                                            tenancy()->end();
                                        }

                                        if($this->tenant_id == 'admin')
                                        {
                                            $current_team_id = 1;
                                        }
                                        else{
                                            $current_team_id = 0;
                                        }

                                        if ($record)
                                        {
                                            $tenant_id = $this->tenant_id;
                                            $tenant = Tenant::find($tenant_id);

                                            $tenant->run(function () use ($record,$datapool_id,$datapool_status)
                                            {
                                                $record->update([
                                                    "datapool_id" => $datapool_id,
                                                    "status"      => $datapool_status
                                                ]);
                                            });
                                        }
                                    }

                                }
                                else
                                {
                                    $datapool_id = "N/A";
                                }

                            }
                            else
                            {

                                $datapool_id = "N/A";
                            }
                        }
                        else
                        {
                            $datapool_id = "N/A";
                        }


                    }

                    return $datapool_id;
                // }
                // catch(Throwable $e)
                // {

                //     Notification::make()
                //     ->title('Error Occurred While Fetching Data Pool ID of '.$record->description)
                //     ->danger()
                //     ->send();

                //     $datapool_id = "N/A";
                //     return $datapool_id;
                // }
            })->label('Data Pool ID')
                ->extraAttributes(['class' => 'p-px text-sm']),
                TextColumn::make('description')->label('Data Pool Name')
                ->extraAttributes(['class' => 'p-px text-sm'])
                ->searchable()
                ->sortable()
                ->default('N/A'),
            TextColumn::make('data_plan_id')->label('Rate Plan')->enum([
                23045 => 'BURO POOL MANUAL TOP UP – 11139460',
                23046 => 'BURO POOL AUTO TOP UP – 11139459'
            ])
                ->extraAttributes(['class' => 'p-px text-sm'])
                ->default('N/A'),
            TextColumn::make('email_address_1')->label('Email #1')
                ->extraAttributes(['class' => 'p-px text-sm'])
                ->default('N/A'),
            TextColumn::make('status')->label('Status')->enum([
                0 => 'Opted-In Pending',
                1 => 'Opted-In',
                2 => 'Opted-Out Pending',
                3 => 'Opted-Out'
            ]),
            TextColumn::make('active_services')
            ->getStateUsing(function (DataPool $record) {
                $mobile_service_count = MobileService::where('datapool_id', $record->id)->count();

                return $mobile_service_count;
            })
        ];
    }
}
