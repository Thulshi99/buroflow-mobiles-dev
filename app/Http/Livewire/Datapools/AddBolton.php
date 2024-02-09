<?php

namespace App\Http\Livewire\Datapools;

use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Livewire\Component;
use App\Models\DataPool;
use App\Models\Tenant;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\AddOnceOffBoltOnRequest;
use Artisaninweb\SoapWrapper\SoapWrapper;
use throwable;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action;

class AddBolton extends Component implements HasForms
{
    use InteractsWithForms;

    public $tenant_id;

    public $datapool_id;
    public $bolt_on_code;
    public $datapool;

    protected $soapWrapper;

    public function mount($datapool_id): void
    {
        if (tenant()) {
            $this->tenant_id = tenant()->id;
        }

        $this->datapool_id = $datapool_id;
        $this->datapool = DataPool::find($this->datapool_id);
    }

    public function render()
    {
        return view('livewire.datapools.add-bolton');
    }

    protected function getFormSchema(): array
    {
        return [
            Grid::make()
                ->schema([
                    Forms\Components\Select::make('bolt_on_code')
                    ->label("Bolt On")
                    ->required()
                    ->autofocus()
                    ->options([
                        1 => 'MSS-CF01-10GB Auto-Block Data Pool Sharing Recurring Bolt-on',
                        2 => 'MSS-CF02-Data Pooling Autoblock Optout'
                    ])
                ])
        ];
    }

    public function submit()
    {
        // try
        // {

            $state = $this->form->getState();
            $bolt_on_code = $state['bolt_on_code'];

            if($bolt_on_code != null)
            {
                // SOAP API Request
                $data = [
                    'username' => env('OCTANE_USERNAME'),
                    'password' => env('OCTANE_PASSWORD'),
                    'cust_no' => env('OCTANE_DEFAULT_CUSTNO','382422'),
                    'lineSeq_no' => $this->datapool->lineseq_no,
                    'bolt_on_code' => $bolt_on_code
                ];

                $request = new AddOnceOffBoltOnRequest($data);

                $endpoint = env('OCTANE_BASE_URL','https://benzine.telcoinabox.com/tiab')."/UtbOrder?wsdl";

                $this->soapWrapper = new SoapWrapper();

                $this->soapWrapper->add('UtbOrderPortBinding', function ($order) use ($endpoint){
                    $order
                        ->wsdl($endpoint) // The WSDL endpoint
                        ->trace(true);  // Optional: (parameter: true/false)
                });

                $response = $this->soapWrapper->call('UtbOrderPortBinding.orderCreate', [
                    new \SoapVar($request->getXmlBody(), XSD_ANYXML)
                ]);

                if ($response != null)
                {
                    //Get OrderID and Error Code
                    $errorCode = $response->return->errorCode;

                    if($errorCode == 0)
                    {
                        $order_id   = $response->return->orderId;

                        if($this->tenant_id != 'admin')
                        {
                            //duplicate data to Admin
                            tenancy()->initialize('admin');
                            $this->datapool->update([
                                "notes" => $order_id
                            ]);

                            tenancy()->end();
                        }

                        if ($state) {
                            $tenant_id = $this->tenant_id;
                            $tenant = Tenant::find($tenant_id);

                            $tenant->run(function () use ($order_id) {
                                $this->datapool->update([
                                    "notes" => $order_id
                                ]);
                            });

                            Notification::make()
                            ->title('Add Data Top-up Request Has Been Sent Successfully')
                            ->success()
                            ->body('It will take around 15 minutes to complete the data top-up.')
                            ->persistent()
                            ->send();
                        }

                    }
                    else
                    {
                        Notification::make()
                        ->title('Error Ocurred While Adding Data Top-up')
                        ->danger()
                        ->persistent()
                        ->send();
                    }

                }
                else
                {
                    Notification::make()
                    ->title('Error Ocurred While Adding Data Top-up')
                    ->danger()
                    ->persistent()
                    ->send();
                }

            }
        // }
        // catch(Throwable $e)
        // {
        //     Notification::make()
        //     ->title('Error Ocurred While Adding Data Top-up')
        //     ->danger()
        //     ->send();
        // }

    }
}
