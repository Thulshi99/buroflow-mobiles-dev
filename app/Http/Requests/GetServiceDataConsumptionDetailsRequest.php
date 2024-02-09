<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetServiceDataConsumptionDetailsRequest extends FormRequest
{
    protected $username;
    protected $password;
    protected $cust_no;

    protected $lineSeq_no;

    public function __construct(array $data)
    {
        $this->username = $data['username'];
        $this->password = $data['password'];
        $this->cust_no   = $data['cust_no'];

        $this->lineSeq_no   = $data['lineSeq_no'];
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
        $xmlTemplate = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:mob="http://mobileservice.frontend.ws.utilibill.com.au/">
                            <soapenv:Header/>
                            <soapenv:Body>
                            <mob:queryBalanceV3>
                                <!--Optional:-->
                                <login>
                                    <password>%password%</password>
                                    <userName>%username%</userName>
                                </login>
                                <!--Optional:-->
                                <queryBalanceRequest>
                                    <custNo>%cust_no%</custNo>
                                    <lineSeqNo>%lineSeq_no%</lineSeqNo>
                                </queryBalanceRequest>
                            </mob:queryBalanceV3>
                            </soapenv:Body>
                        </soapenv:Envelope>';

                return str_replace(
                    ['%password%', '%username%','%cust_no%','%lineSeq_no%'],
                    [$this->password,$this->username, $this->cust_no,$this->lineSeq_no],
                    $xmlTemplate
                );
    }
}
