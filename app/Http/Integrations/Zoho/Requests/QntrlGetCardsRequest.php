<?php

namespace App\Http\Integrations\Zoho\Requests;

use App\Http\Integrations\Zoho\ZohoConnector;
use App\Http\Integrations\Zoho\DataObjects\CardsList;
use Sammyjo20\Saloon\Constants\Saloon;
use Sammyjo20\Saloon\Http\SaloonRequest;
use Sammyjo20\Saloon\Http\SaloonResponse;
use Illuminate\Support\Collection;
use Sammyjo20\Saloon\Traits\Plugins\CastsToDto;

class QntrlGetCardsRequest extends SaloonRequest
{
    use CastsToDto;

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
    protected ?string $method = Saloon::GET;

    /**
     * The endpoint of the request.
     *
     * @return string
     */
    public function defineEndpoint(): string
    {
        return "/job";
    }

    protected function castToDto(SaloonResponse $response): Collection
    {
        return (new Collection(
            items: $response->json('job_list'),
        ))->map(function ($cardslist): CardsList {
            return CardsList::fromSaloon(
                cardslist: $cardslist,
            );
        });
    }
}
