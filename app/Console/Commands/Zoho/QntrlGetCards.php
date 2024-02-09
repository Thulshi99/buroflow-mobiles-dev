<?php

namespace App\Console\Commands\Zoho;

use App\Http\Integrations\Zoho\Requests\QntrlGetCardsRequest;
use App\Http\Integrations\Zoho\DataObjects\CardsList;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class QntrlGetCards extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'qntrl:cards';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get all cards in Qntrl';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $request = new QntrlGetCardsRequest();

        $this->info(
            string: "Fetching all the cards",
        );

        $response = $request->send();

        if ($response->failed())
        {
            throw $response->toException();
        }

        // dd($response->dto());

        $this->table(
            headers: ['ID', 'Name', 'Buroflow Reference', 'Order Status', 'Radius IP', 'Due Date'],
            rows: $response
                ->dto()
                ->map(fn (CardsList $cardslist) =>
                    $cardslist->toArray()
                )->toArray(),
        );

        return self::SUCCESS;
    }
}
