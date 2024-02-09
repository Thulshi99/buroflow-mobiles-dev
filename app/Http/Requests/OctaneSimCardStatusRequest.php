<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OctaneSimCardStatusRequest extends FormRequest
{

    protected $username;
    protected $password;
    protected $simNo;


    public function __construct(array $data)
    {
        $this->username = $data['username'];
        $this->password = $data['password'];
        $this->simNo = $data['simNo'];
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
        $xmlTemplate = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:pr="http://pr.frontend.ws.utilibill.com.au/">
        <soapenv:Header/>
        <soapenv:Body>
           <pr:queryResources>
              <login>
                <password>%password%</password>
                <userName>%username%</userName>
              </login>
              <queryPooledResourcesRequest>
                 <queryValue>%simNo%</queryValue>
              </queryPooledResourcesRequest>
           </pr:queryResources>
        </soapenv:Body>
     </soapenv:Envelope>';

                return str_replace(
                    ['%password%', '%username%','%simNo%'],
                    [$this->password,$this->username,$this->simNo],
                    $xmlTemplate
                );
    }


}
