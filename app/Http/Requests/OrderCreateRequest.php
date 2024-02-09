<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderCreateRequest extends FormRequest
{
    protected $username;
    protected $password;
    protected $custNo;
    protected $simNo;
    protected $msn;


    public function __construct(array $data)
    {
        $this->username = $data['username'];
        $this->password = $data['password'];
        $this->custNo = $data['custNo'];
        $this->simNo = $data['simNo'];
        $this->msn = $data['msn'];
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

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    public function getXmlBody()
    {
        $xmlTemplate = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ord="http://order.frontend.ws.utilibill.com.au/">

        <soapenv:Header />

        <soapenv:Body>

           <ord:orderCreate>

              <login>
                <password>%password%</password>
                <userName>%username%</userName>
              </login>

              <createRequest>

                 <custNo>%custNo%</custNo>

                 <orderType>SRVC_ORD</orderType>

                 <orderAction>ADD_WME_NEW</orderAction>


                 <orderItems>

                    <wmeNewReqItem>

                       <lineType>R</lineType>

                       <lineName>Test Activation</lineName>

                       <department>Systems Team</department>

                       <planNo>23371</planNo>

                       <orderNotes>Test Order Notes</orderNotes>

                       <serviceNotes>Test Service Notes</serviceNotes>

                       <orderItemAddress>

                          <locality>CHATSWOOD</locality>

                          <postcode>2067</postcode>

                          <streetName>west street</streetName>

                          <streetNumber>12</streetNumber>

                          <streetType>AVE</streetType>

                          <subAddressNumber>10</subAddressNumber>

                          <subAddressType>L</subAddressType>

                       </orderItemAddress>

                       <msn>%msn%</msn>

                       <simNo>%simNo%</simNo>

                       <cycleNo>28</cycleNo>

                       <spendCode>80620</spendCode>

                       <notificationEmail>tnnmuhandiram@gmail.com</notificationEmail>

                    </wmeNewReqItem>

                 </orderItems>

              </createRequest>

           </ord:orderCreate>

        </soapenv:Body>

     </soapenv:Envelope>';

                return str_replace(
                    ['%password%', '%username%','%custNo%','%msn%','%simNo%'],
                    [$this->password,$this->username, $this->custNo,$this->msn,$this->simNo],
                    $xmlTemplate
                );
    }
}
