<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddServicesToPoolRequest extends FormRequest
{


    protected $username;
    protected $password;
    protected $cust_no;

    protected $datapool_id;
    protected $service_no;
    protected $current_date;

    public function __construct(array $data)
    {
        $this->username = $data['username'];
        $this->password = $data['password'];
        $this->cust_no   = $data['cust_no'];

        $this->datapool_id   = $data['datapool_id'];
        $this->service_no = $data['service_no'];

        $this->current_date = now()->format('Y-m-d');

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
                            <!--Optional:-->
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
                                    <orderAction>WME_OPT_IN_CONSUMER</orderAction>
                                    <custReqDate>%current_date%</custReqDate>
                                    <orderItems>
                                    <WmeOptInConsumerReqItem>
                                        <providerId>%datapool_id%</providerId>
                                        <serviceNumber>%service_no%</serviceNumber>
                                        </WmeOptInConsumerReqItem>
                                        </orderItems>
                                </orderCreate>
                            </orders>
                        </ord:orderCreateBulk>
                        </soapenv:Body>
                    </soapenv:Envelope>';

                return str_replace(
                    ['%password%', '%username%','%cust_no%','%datapool_id%','%service_no%','%current_date%'],
                    [$this->password,$this->username, $this->cust_no,$this->datapool_id,$this->service_no,$this->current_date],
                    $xmlTemplate
                );
    }
}
