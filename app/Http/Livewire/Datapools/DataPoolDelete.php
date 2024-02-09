<?php

namespace App\Http\Livewire\Datapools;

use Livewire\Component;
use App\Models\DataPool;
use App\Models\MobileService;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use throwable;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action;


class DataPoolDelete extends Component
{

    public $datapool_id;

    protected $listners = ['deleteDatapool'];

    public function render()
    {
        return view('livewire.datapools.data-pool-delete');
    }

    public function mount($datapool_id)
    {
        $this->datapool_id = $datapool_id;
    }


    public function deleteDatapool()
    {
        try
        {
            $dataPool = DataPool::find($this->datapool_id);

            //Check whether datapool contains any mobile services
            $mobile_services_in_pool = MobileService::where('datapool_id', $this->datapool_id)
            ->orderBy('created_at', 'DESC')
            ->get();

            if ($mobile_services_in_pool->count() === 0)
            {
                // Delete the data pool

                $response = $this->deleteApiCall($dataPool);

                if($response != null)
                {
                    if($response['success'] == "true")
                    {
                        $order_id = $response['orderId'];

                        $dataPool->update(['status'=> '3',
                                           'notes' => $order_id]);

                        Notification::make()
                        ->title('Data Pool Has Been Deleted Successfully')
                        ->success()
                        ->actions([
                            Action::make('Ok')
                                ->button()
                                ->url(route('datapools.index'), shouldOpenInNewTab: false)
                            ])
                        ->persistent()
                        ->send();

                        //return redirect()->route('datapools.index')->with('success', 'Data Pool Has Been Deleted Successfully');
                    }
                    else
                    {
                        Notification::make()
                        ->title('An Error Has Been Occurred While Trying To Delete The Pool')
                        ->danger()
                        ->actions([
                            Action::make('Ok')
                                ->button()
                                ->url(route('datapools.show',$this->datapool_id), shouldOpenInNewTab: false)
                            ])
                        ->persistent()
                        ->send();

                        //return Session()->flash('message', 'An Error has been occurred while trying to delete the pool');
                    }
                }
                else
                {
                    Notification::make()
                    ->title('An Error Has Been Occurred While Trying To Delete The Pool')
                    ->danger()
                    ->actions([
                        Action::make('Ok')
                            ->button()
                            ->url(route('datapools.show',$this->datapool_id), shouldOpenInNewTab: false)
                        ])
                    ->persistent()
                    ->send();

                    //return Session()->flash('message', 'An Error has occurred while trying to delete the pool');
                }
            }
            else
            {
                Notification::make()
                ->title('Pool Can Not Be Deleted As It Contains Services')
                ->danger()
                ->actions([
                    Action::make('Ok')
                        ->button()
                        ->url(route('datapools.show',$this->datapool_id), shouldOpenInNewTab: false)
                    ])
                ->persistent()
                ->send();

                //return Session()->flash('message', 'Pool cannot be deleted as it contains services');
            }
        }
        catch(Throwable $e)
        {
            Notification::make()
            ->title('An Error Has Been Occurred While Trying To Delete The Pool')
            ->danger()
            ->actions([
                Action::make('Ok')
                    ->button()
                    ->url(route('datapools.show',$this->datapool_id), shouldOpenInNewTab: false)
                ])
            ->persistent()
            ->send();
        }
    }

    public function deleteApiCall($dataPool)
    {
        $data = null;

        // try
        // {
            $username   = env('OCTANE_USERNAME');
            $password   = env('OCTANE_PASSWORD');
            $cust_no    = env('OCTANE_DEFAULT_CUSTNO','382422');
            $lineseq_no = $dataPool->lineseq_no;

            $client = new Client();

            $apiUrl = 'https://benzine.telcoinabox.com/tiab/api/v1/datapool/disconnect';

            $requestData = [
                'custno' => $cust_no,
                'lineseqno' => $lineseq_no,
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
        // }
        // catch(Throwable $e)
        // {
        //     $data = null;
        // }

        return $data;
    }

}
