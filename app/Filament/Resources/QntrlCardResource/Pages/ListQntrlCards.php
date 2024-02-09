<?php

namespace App\Filament\Resources\QntrlCardResource\Pages;

use App\Filament\Resources\QntrlCardResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListQntrlCards extends ListRecords
{
    protected static string $resource = QntrlCardResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
