<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetServiceDetailRequest extends FormRequest
{
    protected $username;
    protected $password;
    protected $cust_no;
    protected $msn;

    public function __construct(array $data)
    {
        $this->username = $data['username'];
        $this->password = $data['password'];
        $this->cust_no  = $data['cust_no'];
        $this->msn      = $data['msn'];
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
        $xmlTemplate = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:v3="http://v3.service.frontend.ws.utilibill.com.au/">
                            <soapenv:Header/>
                            <soapenv:Body>
                            <v3:getServiceDetail>
                                <login>
                                    <password>%password%</password>
                                    <userName>%username%</userName>
                                </login>
                                <serviceDetail>
                                    <serviceDetails>
                                        <custno>%cust_no%</custno>
                                        <serviceNumber>%msn%</serviceNumber>
                                    </serviceDetails>
                                </serviceDetail>
                            </v3:getServiceDetail>
                            </soapenv:Body>
                        </soapenv:Envelope>';

                return str_replace(
                    ['%password%', '%username%','%cust_no%','%msn%'],
                    [$this->password,$this->username, $this->cust_no, $this->msn],
                    $xmlTemplate
                );
    }
}
