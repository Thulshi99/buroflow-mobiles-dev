<?php

namespace App\Http\Integrations\APIHub\Requests;

use App\Http\Integrations\APIHub\APIHubConnector;
use Sammyjo20\Saloon\Constants\Saloon;
use Sammyjo20\Saloon\Http\SaloonRequest;
use Sammyjo20\Saloon\Traits\Plugins\HasJsonBody;

class SuperloopLocationRequestResults extends SaloonRequest
{
    use HasJsonBody;
    /**
     * The connector class.
     *
     * @var string|null
     */
    protected ?string $connector = APIHubConnector::class;

    /**
     * The HTTP verb the request will use.
     *
     * @var string|null
     */
    protected ?string $method = Saloon::POST;


    public function __construct(
        public string $url
    ) {
    }
    /**
     * The endpoint of the request.
     *
     * @return string
     */
    public function defineEndpoint(): string
    {
        return '/api/product/superloop/sq/location/results';
    }

    public function defaultData(): array
    {
        return [
            'password' => config('services.apihub.password'),
            'url' => $this->url
        ];
    }
}
