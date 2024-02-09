<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddOnceOffBoltOnRequest extends FormRequest
{
    protected $username;
    protected $password;
    protected $cust_no;

    protected $lineSeq_no;
    protected $bolt_on_code;

    public function __construct(array $data)
    {
        $this->username = $data['username'];
        $this->password = $data['password'];
        $this->cust_no   = $data['cust_no'];

        $this->lineSeq_no   = $data['lineSeq_no'];

        //Map Bolton Code
        if($data['bolt_on_code'] == 1)
        {
            $this->bolt_on_code = 'CF01';
        }
        if($data['bolt_on_code'] == 2)
        {
            $this->bolt_on_code = 'CF02';
        }
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
                                <login>
                                    <password>%password%</password>
                                    <userName>%username%</userName>                       <password>8Y10*Wnti$</password>
                                </login>
                                <createRequest>
                                    <custNo>%cust_no%</custNo>
                                    <orderType>SRVC_ORD</orderType>
                                    <orderAction>ADD_ONCEOFF</orderAction>
                                    <orderItems>
                                        <wmeOnceOffBoltonReqItem>
                                        <lineSeqNo>%lineSeq_no%</lineSeqNo>
                                        <onceOffBoltonCode>%bolt_on_code%</onceOffBoltonCode>
                                        </wmeOnceOffBoltonReqItem>
                                    </orderItems>
                                </createRequest>
                            </ord:orderCreate>
                            </soapenv:Body>
                        </soapenv:Envelope>';

                return str_replace(
                    ['%password%', '%username%','%cust_no%','%lineSeq_no%','%bolt_on_code%'],
                    [$this->password,$this->username, $this->cust_no,$this->lineSeq_no,$this->bolt_on_code],
                    $xmlTemplate
                );
    }
}
