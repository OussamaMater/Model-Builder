<?php

namespace App\Actions;

use App\Enums\ModelStatus;
use Filament\Notifications\Notification;

class TrainModel
{
    public function __invoke()
    {
        return function ($record): void {
            sleep(2);
            $record->status = ModelStatus::TRAINING;
            $record->save();

            Notification::make()
                ->info()
                ->title('Requested Training')
                ->body('We will notify you once the model is ready!')
                ->send();
        };
    }
}
