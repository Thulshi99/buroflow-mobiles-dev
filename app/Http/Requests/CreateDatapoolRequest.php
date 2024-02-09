<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateDatapoolRequest extends FormRequest
{

    protected $username;
    protected $password;
    protected $cust_no;

    protected $department;
    protected $data_plan_id;
    protected $email;
    protected $pool_name;

    public function __construct(array $data)
    {
        $this->username = $data['username'];
        $this->password = $data['password'];
        $this->cust_no   = $data['cust_no'];

        $this->department   = $data['department'];
        $this->data_plan_id = $data['data_plan_id'];
        $this->email        = $data['email'];
        $this->pool_name    = $data['pool_name'];
    }


    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            //
        ];
    }

    public function getXmlBody()
    {
        $xmlTemplate = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ord="http://order.frontend.ws.utilibill.com.au/">
                            <soapenv:Header/>
                            <soapenv:Body>
                                <ord:orderCreate>
                                    <!--Optional:-->
                                    <login>
                                        <password>%password%</password>
                                        <userName>%username%</userName>
                                    </login>
                                    <!--Optional:-->
                                    <createRequest>
                                        <!--Optional:-->
                                        <custNo>%cust_no%</custNo>
                                        <!--Optional:-->
                                        <orderType>SRVC_ORD</orderType>
                                        <!--Optional:-->
                                        <orderAction>ADD_WME_NEW</orderAction>
                                        <orderItems>
                                            <WmeNewDataPoolReqItem>
                                                <department>%department%</department>
                                                <planNo>%data_plan_id%</planNo>
                                                <serviceNotes></serviceNotes>
                                                <notificationEmail>%email%</notificationEmail>
                                                <poolName>%pool_name%</poolName>
                                            </WmeNewDataPoolReqItem>
                                        </orderItems>
                                    </createRequest>
                                </ord:orderCreate>
                            </soapenv:Body>
                        </soapenv:Envelope>';

                return str_replace(
                    ['%password%', '%username%','%cust_no%','%department%','%data_plan_id%','%email%','%pool_name%'],
                    [$this->password,$this->username, $this->cust_no,$this->department,$this->data_plan_id,$this->email,$this->pool_name],
                    $xmlTemplate
                );
    }
}
