<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderQueryRequest extends FormRequest
{

    protected $username;
    protected $password;
    protected $order_id;

    public function __construct(array $data)
    {
        $this->username = $data['username'];
        $this->password = $data['password'];
        $this->order_id = $data['order_id'];
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
                            <ord:orderQuery>
                                <login>
                                    <password>%password%</password>
                                    <userName>%username%</userName>
                                </login>
                                <request>
                                    <orderId>%order_id%</orderId>
                                </request>
                            </ord:orderQuery>
                            </soapenv:Body>
                        </soapenv:Envelope>';

                return str_replace(
                    ['%password%', '%username%','%order_id%'],
                    [$this->password,$this->username, $this->order_id],
                    $xmlTemplate
                );
    }
}
