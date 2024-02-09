<?php

namespace App\Http\Controllers;

use App\Mail\ReportMail;
use App\Models\MobileService;
use App\Models\Reseller;
use App\Models\Customer;
use App\Models\ResellerMobileChangeLog;
use App\Models\SimCard;
use Barryvdh\DomPDF\Facade\Pdf;
//use Maatwebsite\Excel\Facades\Excel;
use App\Models\Reports;
use Carbon\Carbon;
use DB;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendPdf;
use App;


class EmailController extends Controller
{
    const REPORT_SIM_CARDS = 3;
    const REPORT_ALLOCATED_SIM = 4;
    const REPORT_UNALLOCATED_SIM = 5;
    const REPORT_SERVICE_DETAIL = 6;
    const REPORT_MOBILE_SERVICES = 7;
    const UPDATE_REPORT = 8;
    const FORMAT_PDF = 'pdf';
    const FORMAT_CSV = 'csv';
    function getUserData(array $data)
    {
        $User = User::find(auth()->user()->id);

        $sim_cards = SimCard::select(
            'simcards.sim_card_code',
            'simcards.created_at',
            'simcards.status',
            'simcards.mobile_number',
            'resellers.reseller_name',
            'shipvias.shipvia_id',

        )
            ->leftJoin('resellers', 'resellers.reseller_id', '=', 'simcards.reseller_id')
            ->leftJoin('shipvias', 'shipvias.id', '=', 'simcards.shipvia_id')
            ->whereBetween('simcards.created_at', [$data['date_from'], date('Y-m-d', strtotime($data['date_to'] . ' + 1 day'))]);

        $allocated_sim_by_resellers = SimCard::select(
            'simcards.sim_card_code',
            'simcards.created_at',
            'simcards.status',
            'simcards.mobile_number',
            'resellers.reseller_name',
            'shipvias.shipvia_id',
        )
            ->leftJoin('resellers', 'resellers.reseller_id', '=', 'simcards.reseller_id')
            ->leftJoin('shipvias', 'shipvias.id', '=', 'simcards.shipvia_id')
            ->where('simcards.status', '=', 'ALLOCATED')
            ->whereBetween('simcards.created_at', [$data['date_from'], date('Y-m-d', strtotime($data['date_to'] . ' + 1 day'))]);

        $unallocated_sim_by_resellers = SimCard::select(
            'simcards.sim_card_code',
            'simcards.created_at',
            'simcards.status',
            'simcards.mobile_number',
            'resellers.reseller_name',
            'shipvias.shipvia_id',
        )
            ->leftJoin('resellers', 'resellers.reseller_id', '=', 'simcards.reseller_id')
            ->leftJoin('shipvias', 'shipvias.id', '=', 'simcards.shipvia_id')
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

    public function emailPdf(Request $request)
    {

        $data = [
            "report_type" => $request->report_type,
            "format" => $request->format,
            "date_from" => $request->date_from,
            "date_to" => $request->date_to,
            "emails" => $request->emails,

        ];

        $maildata = [
            "email" => $data["emails"],
            "title" => "Email Testing Title",
            "body" => "Email Testing Body",
        ];

        if ($data['report_type'] == self::REPORT_SIM_CARDS) {
            $simCards = $this->getUserData($data);

            if (empty($simCards['simCards'])) {
                return redirect()->back()->withErrors(['error' => 'No data available.']);
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
                return redirect()->back()->withErrors(['error' => 'No data available.']);
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
                return redirect()->back()->withErrors(['error' => 'No data available.']);
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
                return redirect()->back()->withErrors(['error' => 'No data available.']);
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
                return redirect()->back()->withErrors(['error' => 'No data available.']);
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
                return redirect()->back()->withErrors(['error' => 'No data available.']);
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


    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
