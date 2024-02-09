<?php

namespace App\Http\Integrations\Zoho;

use Sammyjo20\Saloon\Http\SaloonConnector;
use Sammyjo20\Saloon\Traits\Plugins\AcceptsJson;
use Njoguamos\LaravelZohoOauth\Models\ZohoOauth;

class ZohoConnector extends SaloonConnector
{
    use AcceptsJson;

    protected array $requests = [];

    /**
     * The Base URL of the API.
     *
     * @return string
     */
    public function defineBaseUrl(): string
    {
        return 'https://orchestly.zoho.com.au/blueprint/api/buroservaustralia';
    }

    /**
     * The headers that will be applied to every request.
     *
     * @return string[]
     */
    public function defaultHeaders(): array
    {
        $token = ZohoOauth::latest()->first();
        $accessToken = 'Zoho-oauthtoken ' . $token->access_token;
        // "Zoho-oauthtoken 1000.27cb28ac001d4f1b610f06c414fc5d5a.8fa8f34f61e4c2cc9c466e8aaccba395"

        return [
            
            // 'Content-Type' => 'application/x-www-form-urlencoded',
            // 'Accept' => 'application/json',
            'Authorization' => $accessToken,
        ];
    }

    /**
     * The config options that will be applied to every request.
     *
     * @return string[]
     */
    public function defaultConfig(): array
    {
        return [
            'timeout' => 30,
            'verify' => false,
        ];
    }
}
