<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RemoveServicesFromPoolRequest extends FormRequest
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
                        <ord:orderCreateBulk>
                            <login>
                                <password>%password%</password>
                                <userName>%username%</userName>
                            </login>
                            <!--Optional:-->
                            <orders>
                                <!--Zero or more repetitions:-->
                                <orderCreate>
                                        <custNo>%cust_no%</custNo>
                                        <orderType>SRVC_ORD</orderType>
                                            <orderAction>WME_OPT_OUT_CONSUMER</orderAction>
                                        <orderItems>
                                            <WmeOptOutConsumerReqItem>
                                                <serviceNumber>%service_no%</serviceNumber>
                                            </WmeOptOutConsumerReqItem>
                                        </orderItems>
                                </orderCreate>
                            </orders>
                        </ord:orderCreateBulk>
                        </soapenv:Body>
                    </soapenv:Envelope>';

                return str_replace(
                    ['%password%', '%username%','%cust_no%','%service_no%'],
                    [$this->password,$this->username, $this->cust_no,$this->service_no],
                    $xmlTemplate
                );
    }
}
