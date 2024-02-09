<?php

namespace App\Http\Controllers;

use App\Models\DataPool;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Throwable;

class DataPoolController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('datapools.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('datapools.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('datapools.show',['datapool' => $id]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Show the list for managing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function manage($id)
    {
        return view('datapools.manage',['datapool' => $id]);
    }

    /**
     * Show the list for adding the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function addService($id)
    {
        return view('datapools.mobile-services.add',['datapool' => $id]);
    }

       /**
     * Add Services List from Excel File.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function addServicesBulk($id)
    {
        return view('datapools.mobile-services.add_files_from_file',['datapool' => $id]);
    }

     /**
     * Add Bolton for specified data pool.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function addBolton($id)
    {
        return view('datapools.add_bolton',['datapool_id' => $id]);
    }



     /**
     * Add Data Topup for specified data pool.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function addDataTopUp($id)
    {
        return view('datapools.add_data_topup',['datapool' => $id]);
    }

     /**
     * Modify Data Limit Configurations
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function dataLimitConfig($id)
    {
        return view('datapools.data_limit_config',['datapool' => $id]);
    }

     /**
     * Show Data Consumption of Services
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function dataConsumption($id)
    {
        // try
        // {
            //Set current Billing Cycle
            $currentDate = Carbon::now();
            $firstDayOfMonth = $currentDate->firstOfMonth();
            $startDate = $firstDayOfMonth->format('Y-m-d');

            $start_date_billing_cycle = $startDate;

            $startDate = \Carbon\Carbon::parse($start_date_billing_cycle);

            $next_date_billing_cycle =  $startDate->addMonth()->startOfMonth();

            $days_remaining = $currentDate->diffInDays($next_date_billing_cycle);

            $total_allowance = null;
            $remaining_data  = null;
            $data_usage      = null;

            $datapool = DataPool::find($id);

            //Get Data Consumption
            $username = env('OCTANE_USERNAME');
            $password = env('OCTANE_PASSWORD');

            $response = Http::withBasicAuth($username, $password)
                        ->get('https://benzine.telcoinabox.com/tiab/api/v1/datapool', [
                            'custno'    => env('OCTANE_DEFAULT_CUSTNO','382422'),
                            'lineseqno' => $datapool->lineseq_no
                        ]);

            if ($response->OK()) {
                $responseData = $response->json();

                if($responseData['success'] == "true")
                {
                    if(isset($responseData['provider']['dataMaxCap']))
                    {
                        $total_allowance = $responseData['provider']['dataMaxCap'];
                        $total_allowance = $this->extractNumericValue($total_allowance);
                        $total_allowance = number_format($total_allowance, 2);
                    }

                    if(isset($responseData['provider']['dataMaxCap']))
                    {
                        $data_usage = $responseData['provider']['dataUsage'];
                        $data_usage = $this->extractNumericValue($data_usage);
                        $data_usage = number_format($data_usage, 2);
                    }


                    if ($total_allowance !== null && $data_usage !== null && $total_allowance !== 0 && $data_usage !== 0) {

                        $remaining_data = $total_allowance - $data_usage;

                        $remaining_data =  number_format($remaining_data, 2)." GB";
                    }

                }
            }

            return view('datapools.data_consumption',['datapool_id' => $id,
                                                    'start_date_billing_cycle' => $start_date_billing_cycle,
                                                    'days_remaining' => $days_remaining,
                                                    'total_allowance'=> $total_allowance,
                                                    'data_usage'=> $data_usage,
                                                    'remaining_data' => $remaining_data]);
        // }
        // catch(Throwable $e)
        // {
        //     return view('datapools.data_consumption',['datapool_id' => $id,
        //                 'start_date_billing_cycle' => $start_date_billing_cycle,
        //                 'days_remaining' => $days_remaining,
        //                 'total_allowance'=> $total_allowance,
        //                 'data_usage'=> $data_usage,
        //                 'remaining_data' => $remaining_data]);
        // }
    }

    function extractNumericValue($dataString)
    {
        // Extract numeric part before "GB"
        preg_match('/([0-9.]+)\s*GB/i', $dataString, $matches);

        // Return the extracted numeric value
        return (float) $matches[1] ?? 0;
    }
}
