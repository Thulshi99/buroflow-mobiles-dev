<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PortBindingServiceRequest extends FormRequest
{

    protected $username;
    protected $password;
    protected $custNo;


    public function __construct(array $data)
    {
        $this->username = $data['username'];
        $this->password = $data['password'];
        $this->custNo = $data['custNo'];
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
        $xmlTemplate = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ser="http://service.frontend.ws.utilibill.com.au/">
                    <soapenv:Header/>
                    <soapenv:Body>
                        <ser:getServices>
                            <login>
                                <password>%password%</password>
                                <userName>%username%</userName>
                            </login>
                            <custNo>%custNo%</custNo>
                        </ser:getServices>
                    </soapenv:Body>
                </soapenv:Envelope>';

                return str_replace(
                    ['%password%', '%username%','%custNo%'],
                    [$this->password,$this->username, $this->custNo],
                    $xmlTemplate
                );
    }
}
