<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSimCardRequest;
use App\Http\Requests\UpdateSimCardRequest;
use App\Models\SimCard;
use App\Models\SimBatchOrder;
use App\Imports\SimcardImport;
use Maatwebsite\Excel\Facades\Excel;
use File;
class SimCardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('simcards.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('simcards.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreSimCardRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(array $data)
    {

        $sim_batch_order = new SimBatchOrder();
        $sim_batch_order->company_id = 1;
        $sim_batch_order->ship_via = $data['shipvia_id'];
        $sim_batch_order->first_sim_card_id = isset($data['first_sim_card_id']) ? $data['first_sim_card_id'] : 0;
        $sim_batch_order->last_sim_card_id = isset($data['last_sim_card_id']) ? $data['last_sim_card_id'] : 0;
        $sim_batch_order->reseller_id = $data['reseller_id'];
        $sim_batch_order->date_ordered = is_null($data['date_ordered']) ? date("Y-m-d") : $data['date_ordered'];
        $sim_batch_order->date_received = is_null($data['date_received']) ? date("Y-m-d") : $data['date_received'];
        $sim_batch_order->save();

        $last_batch_order_id = SimBatchOrder::latest()->first()->id;

        if($data['batch_or_single'] == true){ // batch upload
            if($data['csv_upload_or_not'] == true){ // upload csv file
                $file_path = storage_path('app/public/'.$data['file']);
                $simcard_import = new SimcardImport($last_batch_order_id+1,$data['reseller_id'],$data['shipvia_id']);
                Excel::import($simcard_import, $file_path);
                if(file_exists($file_path)){
                    File::delete($file_path);
                }
            }else{ // import without csv file
                $i = $data['first_sim_card_id'];
                for ($i; $i <= $data['last_sim_card_id']; $i++) {
                    $simcard = new SimCard();
                    $simcard->puk_code = rand(100000, 999999);
                    $simcard->sim_card_code = $i;
                    $simcard->batch_number = $last_batch_order_id+1;
                    $simcard->mobile_number = 'NA';
                    $simcard->status = 'available';
                    $simcard->shipvia_id = $data['shipvia_id'];
                    $simcard->reseller_id = $data['reseller_id'];
                    $simcard->company_id = 1;
                    $simcard->save();
                }

            }
        }else{ // single upload
            $simcard = new SimCard();
            $simcard->puk_code = rand(100000, 999999);
            $simcard->sim_card_code = $data['single_sim_card_id'];
            $simcard->batch_number = $last_batch_order_id+1;
            $simcard->mobile_number = 'NA';
            $simcard->status = 'available';
            $simcard->shipvia_id = $data['shipvia_id'];
            $simcard->reseller_id = $data['reseller_id'];
            $simcard->company_id = 1;
            $simcard->save();
        }

        return to_route('simcards.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SimCard  $simCard
     * @return \Illuminate\Http\Response
     */
    public function show(SimCard $simCard)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SimCard  $simCard
     * @return \Illuminate\Http\Response
     */
    public function edit(SimCard $simCard)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateSimCardRequest  $request
     * @param  \App\Models\SimCard  $simCard
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSimCardRequest $request, SimCard $simCard)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SimCard  $simCard
     * @return \Illuminate\Http\Response
     */
    public function destroy(SimCard $simCard)
    {
        //
    }
}
