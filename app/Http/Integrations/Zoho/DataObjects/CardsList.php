<?php 
namespace App\Http\Integrations\Zoho\DataObjects;

use Illuminate\Support\Facades\Date;

class CardsList
{
    public function __construct(
        public int $id,
        public string $name,
        public string $buroflowReference,
        public string $orderStatus,
        public string $radiusIP,
        public string $duedate,
    ) {}

    public static function fromSaloon(array $cardslist): static 
    {
        return new static(
            id: intval(data_get($cardslist, "id")),
            name: strval(data_get($cardslist, "title")),
            buroflowReference: strval(data_get($cardslist['fields'], "customfield_shorttext41")),
            orderStatus: strval(data_get($cardslist['fields'], "customfield_dropdown20")),
            radiusIP: strval(data_get($cardslist['fields'], "customfield_shorttext24")),
            duedate: strval(data_get($cardslist, "due_date"))
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'buroflowReference' => $this->buroflowReference,
            'orderStatus' => $this->orderStatus,
            'radiusIP' => $this->radiusIP,
            'duedate' => $this->duedate,
        ];
    }
}
