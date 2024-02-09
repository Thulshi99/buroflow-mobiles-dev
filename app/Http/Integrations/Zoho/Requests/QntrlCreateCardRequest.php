<?php

namespace App\Http\Integrations\Zoho\Requests;

use App\Http\Integrations\Zoho\ZohoConnector;
use Sammyjo20\Saloon\Constants\Saloon;
use Sammyjo20\Saloon\Http\SaloonRequest;
// use Sammyjo20\Saloon\Traits\Plugins\HasJsonBody;
use Sammyjo20\Saloon\Traits\Plugins\HasFormParams;

class QntrlCreateCardRequest extends SaloonRequest
{
    use HasFormParams;


    /**
     * The connector class.
     *
     * @var string|null
     */
    protected ?string $connector = ZohoConnector::class;

    /**
     * The HTTP verb the request will use.
     *
     * @var string|null
     */
    protected ?string $method = Saloon::POST;

    /**
     * The endpoint of the request.
     *
     * @return string
     */
    public function defineEndpoint(): string
    {
        return '/job';
    }

    public function defaultData(): array
    {
        return [
            'layout_id' => '1399000000082001',
            'record_owner' => '1399000000168005',
            'team_id' => '1399000000027225',
            // 'order_status' => '1399000000201373',
            'title' => 'New Test From Buroflow 1'
        ];
    }


}
