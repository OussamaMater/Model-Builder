<?php

namespace App\Filament\Resources\IAModelResource\Pages;

use App\Filament\Resources\IAModelResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListIAModels extends ListRecords
{
    protected static string $resource = IAModelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
