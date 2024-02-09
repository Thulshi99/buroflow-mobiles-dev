<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use SimpleXMLElement;
use App\Models\MobilePlans;
use App\Models\VendorProduct;
use App\Models\ResellerWholesalePackage;
use App\Models\RetailPackage;
use App\Models\WholesalePackage;
use App\Models\RetailPackageOption;
use App\Models\WholesalePackageOption;


class MobileplanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('mobileplans.index');
    }

    public function create()
    {
        return view('mobileplans.create');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createOne()
    {
        $xmlString = '   <S:Envelope xmlns:S="http://schemas.xmlsoap.org/soap/envelope/">
        <S:Body>
           <ns2:getGroupPlansResponse xmlns:ns2="http://plan.frontend.ws.utilibill.com.au/">
              <return>
                 <errorCode>0</errorCode>
                 <groupName>Buroserv Australia Pty Ltd</groupName>
                 <groupNo>744</groupNo>
                 <groupPlan>
                    <planNo>11134713</planNo>
                    <planName>100GB DATA ONLY MANUAL</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11134200</planNo>
                    <planName>10GB + Voice and Data National Calls/SMS</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11134206</planNo>
                    <planName>10GB = Voice and Data National calls/SMS</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11134700</planNo>
                    <planName>10GB DATA ONLY AUTO</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11134708</planNo>
                    <planName>10GB DATA ONLY MANUAL</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11134204</planNo>
                    <planName>130GB + Voice and Data National Calls/SM</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11134705</planNo>
                    <planName>130GB DATA ONLY AUTO</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11134001</planNo>
                    <planName>1GB + Unlimited National Calls/SMS</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11134198</planNo>
                    <planName>1GB + Voice and Data National Calls/SMS-</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11134698</planNo>
                    <planName>1GB DATA ONLY AUTO</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11134706</planNo>
                    <planName>1GB DATA ONLY MANUAL</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11134059</planNo>
                    <planName>1GB+ Voice and Data National Calls/SMS</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11134701</planNo>
                    <planName>20 GB DATA ONLY AUTO</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11134201</planNo>
                    <planName>20GB + Voice and Data National Calls/SMS</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11134207</planNo>
                    <planName>20GB +Voice and Data National Calls/SMS/</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11134709</planNo>
                    <planName>20GB DATA ONLY MANUAL</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11134202</planNo>
                    <planName>30GB + Voice and Data National Calls/SMS</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11134702</planNo>
                    <planName>30GB DATA ONLY AUTO</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11134710</planNo>
                    <planName>30GB DATA ONLY MANUAL</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11134199</planNo>
                    <planName>3GB + Voice and Data National Calls/SMS-</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11134205</planNo>
                    <planName>3GB +Voice and Data National Calls/SMS-M</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11134703</planNo>
                    <planName>40GB DATA ONLY AUTO</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11134711</planNo>
                    <planName>40GB DATA ONLY MANUAL</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11134203</planNo>
                    <planName>60GB + Voice and Data National /SMS Unli</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11134704</planNo>
                    <planName>60GB DATA ONLY AUTO</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11134712</planNo>
                    <planName>60GB DATA ONLY MANUAL</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11134240</planNo>
                    <planName>60GB V&amp;D AUTO</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11134247</planNo>
                    <planName>60GB V&amp;D MANUAL</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11139462</planNo>
                    <planName>BURO 4G   180GB V&amp;D MANUAL</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11139463</planNo>
                    <planName>BURO 4G  180GB V&amp;D  AUTO</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11134238</planNo>
                    <planName>BURO 4G  22GB  V&amp;D AUTO</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11134245</planNo>
                    <planName>BURO 4G  22GB  V&amp;D MANUAL</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11134083</planNo>
                    <planName>BURO 4G  42GB  V&amp;D AUTO</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11134699</planNo>
                    <planName>BURO 4G  MBB 3GB AUTO</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11134707</planNo>
                    <planName>BURO 4G  MBB 3GB MANUAL</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11139451</planNo>
                    <planName>BURO 4G  MBB 42GB  MANUAL</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11134237</planNo>
                    <planName>BURO 4G 10GB V&amp;D AUTO</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11134244</planNo>
                    <planName>BURO 4G 10GB V&amp;D MANUAL</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11139470</planNo>
                    <planName>BURO 4G 180GB MBB AUTO</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11139469</planNo>
                    <planName>BURO 4G 180GB MBB MANUAL</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11139435</planNo>
                    <planName>BURO 4G 180GB V&amp;D  AUTO</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11139434</planNo>
                    <planName>BURO 4G 180GB V&amp;D MANUAL</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11134235</planNo>
                    <planName>BURO 4G 1GB V&amp;D AUTO</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11134242</planNo>
                    <planName>BURO 4G 1GB V&amp;D MANUAL</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11134239</planNo>
                    <planName>BURO 4G 32GB V&amp;D AUTO</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11134246</planNo>
                    <planName>BURO 4G 32GB V&amp;D MANUAL</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11134236</planNo>
                    <planName>BURO 4G 3GB V&amp;D AUTO</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11134243</planNo>
                    <planName>BURO 4G 3GB V&amp;D MANUAL</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11139502</planNo>
                    <planName>BURO 4G 42GB V&amp;D MANUAL</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11139457</planNo>
                    <planName>BURO 4G MBB  POOL 3GB</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11139467</planNo>
                    <planName>BURO 4G MBB 10GB  AUTO</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11139436</planNo>
                    <planName>BURO 4G MBB 10GB  MANUAL</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11134686</planNo>
                    <planName>BURO 4G MBB 22GB  AUTO</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11134685</planNo>
                    <planName>BURO 4G MBB 22GB MANUAL</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11139448</planNo>
                    <planName>BURO 4G MBB 32GB  AUTO</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11134695</planNo>
                    <planName>BURO 4G MBB 32GB  MANUAL</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11139449</planNo>
                    <planName>BURO 4G MBB 3GB  MANUAL</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11139450</planNo>
                    <planName>BURO 4G MBB 3GB AUTO</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11139452</planNo>
                    <planName>BURO 4G MBB 42GB  AUTO</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11139453</planNo>
                    <planName>BURO 4G MBB 90GB  MANUAL</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11139454</planNo>
                    <planName>BURO 4G MBB 90GB AUTO</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11138770</planNo>
                    <planName>BURO 4G MBB POOL 10Gb</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11139456</planNo>
                    <planName>BURO 4G MBB POOL 20Gb</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11139458</planNo>
                    <planName>BURO 4G MBB POOL 40Gb</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11134577</planNo>
                    <planName>BURO 4G V&amp;D  POOL 10Gb</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11134576</planNo>
                    <planName>BURO 4G V&amp;D  POOL 3GB</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11134578</planNo>
                    <planName>BURO 4G V&amp;D POOL 20Gb</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11134579</planNo>
                    <planName>BURO 4G V&amp;D POOL 40Gb</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11136063</planNo>
                    <planName>BURO 5G   120 GB  V&amp;D MANUAL</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11136064</planNo>
                    <planName>BURO 5G  60 GB V&amp;D AUTO</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11139455</planNo>
                    <planName>BURO 5G  MBB POOL 60GB</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11139461</planNo>
                    <planName>BURO 5G 120 GB V&amp;D AUTO</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11134241</planNo>
                    <planName>BURO 5G 150GB V&amp;D AUTO</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11134248</planNo>
                    <planName>BURO 5G 150GB V&amp;D MANUAL</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11139466</planNo>
                    <planName>BURO 5G 32GB MBB CAMPAIGN MANUAL</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11139465</planNo>
                    <planName>BURO 5G 32GB V&amp;D CAMPAIGN AUTO</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11139464</planNo>
                    <planName>BURO 5G 32GB V&amp;D CAMPAIGN MANUAL</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11134690</planNo>
                    <planName>BURO 5G MBB 150GB  AUTO</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11134682</planNo>
                    <planName>BURO 5G MBB 150GB  MANUAL</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11139403</planNo>
                    <planName>BURO 5G V&amp;D POOL 60GB</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11139404</planNo>
                    <planName>BURO 5GB MBB CAMPAIGN 32GB</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11139405</planNo>
                    <planName>BURO 5GB V&amp;D CAMPAIGN 32 GB AUTO</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11139406</planNo>
                    <planName>BURO 5GB V&amp;D CAMPAIGN 32 GB MANUAL</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11139459</planNo>
                    <planName>BURO POOL AUTO TOP UP</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11139460</planNo>
                    <planName>BURO POOL MANUAL TOP UP</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11134474</planNo>
                    <planName>Buroserv 10GB Data Only</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11134056</planNo>
                    <planName>Buroserv 10GB Unlimited</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11134861</planNo>
                    <planName>Buroserv 10GB Unlimited + IDD</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11134067</planNo>
                    <planName>Buroserv 10GB Unlimited Manual</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11134472</planNo>
                    <planName>Buroserv 130GB Data Only Auto</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11134473</planNo>
                    <planName>Buroserv 130GB Data Only Manual</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11134061</planNo>
                    <planName>Buroserv 130GB Unlimited Auto</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11134064</planNo>
                    <planName>Buroserv 130GB Unlimited Manual</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11134476</planNo>
                    <planName>Buroserv 15GB Data Only</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11134068</planNo>
                    <planName>Buroserv 15GB Unlimited</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11134072</planNo>
                    <planName>Buroserv 18GB Unlimited Auto</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11134475</planNo>
                    <planName>Buroserv 1GB Data Only</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11134058</planNo>
                    <planName>Buroserv 1GB Unlimited</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11134718</planNo>
                    <planName>Buroserv 1GB Unlimited + IDD</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11134495</planNo>
                    <planName>Buroserv 1GB Unlimited Manual</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11134074</planNo>
                    <planName>Buroserv 22 GB Unlimited Manual</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11134477</planNo>
                    <planName>Buroserv 22GB Data Only</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11134076</planNo>
                    <planName>Buroserv 22GB Unlimited</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11134077</planNo>
                    <planName>Buroserv 22GB Unlimited Manual</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11134478</planNo>
                    <planName>Buroserv 32GB Data Only</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11134078</planNo>
                    <planName>Buroserv 32GB Unlimited</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11133951</planNo>
                    <planName>Buroserv 3Gb Included Data</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11134479</planNo>
                    <planName>Buroserv 42GB Data Only</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11134862</planNo>
                    <planName>Buroserv 42GB Unlimited + IDD</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11138774</planNo>
                    <planName>BUROSERV 5G DATA ONLY POOL 60 GB</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11134480</planNo>
                    <planName>Buroserv 5GB Data Only</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11134079</planNo>
                    <planName>Buroserv 5GB Unlimited</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11134719</planNo>
                    <planName>Buroserv 5GB Unlimited + IDD</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11134082</planNo>
                    <planName>Buroserv 8GB Unlimited Auto</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11134481</planNo>
                    <planName>Buroserv 90GB Data Only</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11134081</planNo>
                    <planName>Buroserv 90GB Unlimited</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11138771</planNo>
                    <planName>BUROSERV DATA  ONLY POOL 20 GB</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11138772</planNo>
                    <planName>BUROSERV DATA ONLY POOL 3 GB</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11138773</planNo>
                    <planName>BUROSERV DATA ONLY POOL 40 GB</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11135887</planNo>
                    <planName>Buroserv NBN 100Mbps Ultd (1015)</planName>
                    <usageType>NN</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11137395</planNo>
                    <planName>Buroserv NBN 12Mbps 100GB</planName>
                    <usageType>NN</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11135730</planNo>
                    <planName>Buroserv NBN 12Mbps 15GB</planName>
                    <usageType>NN</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11137397</planNo>
                    <planName>Buroserv NBN 12Mbps 250GB</planName>
                    <usageType>NN</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11137396</planNo>
                    <planName>Buroserv NBN 12Mbps 500GB</planName>
                    <usageType>NN</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11134613</planNo>
                    <planName>POOLING PLAN AUTO TOP UP</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11134612</planNo>
                    <planName>POOLING PLAN MANUAL TOP UP</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>370654</planNo>
                    <planName>Sonar Entice Endpoint Zero Retail</planName>
                    <usageType>CY</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>370794</planNo>
                    <planName>Sonar Entice Trunk Zero Retail</planName>
                    <usageType>CY</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11134693</planNo>
                    <planName>Template 10GB MBB no Topup New</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11134691</planNo>
                    <planName>Template 1GB MBB no Topup New</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11134683</planNo>
                    <planName>Template 1GB MBB with Topup New</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11134694</planNo>
                    <planName>Template 20GB MBB no Topup New</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11134687</planNo>
                    <planName>Template 30GB MBB with Topup New</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11134692</planNo>
                    <planName>Template 3GB MBB no Topup New</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11134684</planNo>
                    <planName>Template 3GB MBB with Topup New</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11134696</planNo>
                    <planName>Template 40GB MBB no Topup New</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11134688</planNo>
                    <planName>Template 40GB MBB with Topup New</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11134697</planNo>
                    <planName>Template 60GB MBB no Topup New</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
                 <groupPlan>
                    <planNo>11134689</planNo>
                    <planName>Template 60GB MBB with Topup New</planName>
                    <usageType>MB</usageType>
                 </groupPlan>
              </return>
           </ns2:getGroupPlansResponse>
        </S:Body>
     </S:Envelope>';
        $xml = new SimpleXMLElement($xmlString);
        $xpath = $xml->xpath('//groupPlan');
        foreach ($xpath as $element) {
            if (!MobilePlans::where('data_plan_code', (string) $element->planNo)->exists()) {
                $mobile_plan = new MobilePlans();
                $mobile_plan->data_plan_code = (string) $element->planNo;
                $mobile_plan->plan_name = (string) $element->planName;
                $mobile_plan->usage_type = (string) $element->usageType;
                $mobile_plan->save();
            }

        }
        return to_route('mobileplans.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(array $data)
    {

        $vendor_products = new VendorProduct();
        $vendor_products->vendor_id = 1;
        $vendor_products->vendor_product_code = $data['plan_code'];
        $vendor_products->vendor_product_name = $data['plan_name'];
        $vendor_products->prepaid =  $data['is_prepaid'];
        $vendor_products->package_type =  $data['plan_type'];
        $vendor_products->save();

        //get last vendor product id
        $vendor_product_id = VendorProduct::latest()->first()->id;


        if($data['plan_type'] === 'wholesale'){
            $reseller_wholesale_package = new WholesalePackage();
            $reseller_wholesale_package->wholesale_pakage_code = $data['plan_code'];
            $reseller_wholesale_package->wholesale_pakage_name = $data['plan_name'];
            $reseller_wholesale_package->reseller_id = $data['reseller_id'];
            $reseller_wholesale_package->vendor_inventory_id = $vendor_product_id;
            $reseller_wholesale_package->datapool = $data['is_data_pool'];
            $reseller_wholesale_package->save();

            //get last wholesale package id
            $reseller_wholesale_package_id = WholesalePackage::latest()->first()->id;

            $package_option = new WholesalePackageOption();
            $package_option->wholesale_pakage_id = $reseller_wholesale_package_id ;
            $package_option->wholesale_pakage_code = $data['package_option_code'];
            $package_option->wholesale_pakage_option_name = $data['package_option'];
            $package_option->price = $data['package_option_price'];

            $package_option->save();

        }else{
            $retail_package = new RetailPackage();
            $retail_package->retail_pakage_code = $data['plan_code'];
            $retail_package->retail_pakage_name = $data['plan_name'];
            $retail_package->reseller_id = $data['reseller_id'];
            $retail_package->vendor_inventory_id = $vendor_product_id;
            $retail_package->save();

            //get last retail package id
            $retail_package_id = RetailPackage::latest()->first()->id;

            $package_option = new RetailPackageOption();
            $package_option->retail_package_id = $retail_package_id ;
            $package_option->retail_pakage_code = $data['package_option_code'];
            $package_option->retail_pakage_option_name = $data['package_option'];
            $package_option->price = $data['package_option_price'];
            $package_option->save();
        }

        return to_route('mobileplans.index');
    }

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


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function assignMobilePlan()
    {
        return view('mobileplans.assign');
    }


}
