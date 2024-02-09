<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OctaneSelectReserveNumberRequest extends FormRequest
{

    protected $username;
    protected $password;
    protected $mobileNo;


    public function __construct(array $data)
    {
        $this->username = $data['username'];
        $this->password = $data['password'];
        $this->mobileNo = $data['mobileNo'];
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
           <pr:selectResource>
              <login>
                <password>%password%</password>
                <userName>%username%</userName>
              </login>
              <selectPooledResourceRequest>
                 <resource>%mobileNo%</resource>
                 <resourceType>WME_MSN</resourceType>
              </selectPooledResourceRequest>
           </pr:selectResource>
        </soapenv:Body>
     </soapenv:Envelope>';

                return str_replace(
                    ['%password%', '%username%','%mobileNo%'],
                    [$this->password,$this->username, $this->mobileNo],
                    $xmlTemplate
                );
    }


}
