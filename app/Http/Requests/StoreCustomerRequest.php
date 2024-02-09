<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


class StoreCustomerRequest extends FormRequest
{

    protected $first_name;
    protected $last_name;
    protected $title;
    protected $gender;
    protected $home_phone;
    protected $current_phone_number;
    protected $line_one;
    protected $line_two;
    protected $line_three;
    protected $city;
    protected $state;
    protected $postal_code;
    protected $country;
    protected $paper_bill;
    protected $email_bill;
    protected $date_of_birth;
    protected $email;


    public function __construct(array $data)
    {
        $this->first_name = $data['first_name'];
        $this->last_name = $data['last_name'];
        $this->title = $data['title'];
        $this->gender = $data['gender'];
        $this->home_phone = $data['home_phone'];
        $this->current_phone_number = $data['current_phone_number'];
        $this->line_one = $data['line_one'];
        $this->line_two = $data['line_two'];
        $this->line_three = $data['line_three'];
        $this->city = $data['city'];
        $this->state = $data['state'];
        $this->postal_code = $data['postal_code'];
        $this->country = $data['country'];
        $this->paper_bill = $data['paper_bill'];
        $this->email_bill = $data['email_bill'];
        $this->date_of_birth = $data['date_of_birth'];
        $this->email = $data['email'];
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

    public function genearateSelcommAccountCreateBody(){
        $accountData = [
            "BusinessUnitCode" => 'HI',
            "Type" =>  'Corporation',
            "SubTypeId" => 'CO',
            "StatusId" => 'I',
            "Name" => $this->first_name." ".$this->last_name,
            "FirstName" => $this->first_name,
            "Title" => $this->title ,
            "DateOfBirth" => $this->date_of_birth ,
            "Gender" => $this->gender ,
            "TradingName" => $this->first_name." ".$this->last_name,
            "BusinessNumber" => '69131636836',
            "Email" => $this->email ,
            "HomePhone" => $this->home_phone ?? '61294757575',
            "WorkPhone" => $this->current_phone_number,
            "MobilePhone" => $this->current_phone_number,
            "BillingAddress" => [
                "Address1" => $this->line_one,
                "Address2" => $this->line_two,
                "Suburb" => $this->line_three,
                "City" => $this->city,
                "State" => $this->state,
                "Postcode" => $this->postal_code,
                "CountryCode" => $this->country ?? 'AU'
            ],
            "StreetAddress" => [
                "Address1" => $this->line_one,
                "Address2" => $this->line_two,
                "Suburb" => $this->line_three,
                "City" => $this->city,
                "State" => $this->state,
                "Postcode" => $this->postal_code,
                "CountryCode" => $this->country ?? 'AU'
            ],
            "PaperBill" => $this->paper_bill ?? false,
            "EmailBill" => $this->email_bill ?? true,
            "BillingCycleCode" => 'TE',
            "DealerId" =>  null,
            "TaxId" =>  1,
            "TimeZoneId" => null,
            "CreditLimit" => 0,
            "ParentId" => null,
            "ExternalPayId" => null
        ];

        return response()->json($accountData);
    }

}
