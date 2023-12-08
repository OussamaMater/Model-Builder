<?php

namespace App\Filament\Resources\IAModelResource\Pages;

use App\Filament\Resources\IAModelResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditIAModel extends EditRecord
{
    protected static string $resource = IAModelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
