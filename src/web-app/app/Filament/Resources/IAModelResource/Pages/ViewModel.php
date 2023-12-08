<?php

namespace App\Filament\Resources\IAModelResource\Pages;

use Filament\Actions;
use App\Models\IAModel;
use App\Jobs\RequestTest;
use App\Enums\ModelStatus;
use Filament\Actions\Action;
use App\Jobs\RequestTraining;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Filament\Forms\Components\FileUpload;
use App\Filament\Resources\IAModelResource;

class ViewModel extends ViewRecord
{
    protected static string $resource = IAModelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->form(IAModel::getForm())
                ->icon('heroicon-o-pencil-square')
                ->slideOver(),

            Action::make('Train')
                ->action(function ($record): void {
                    RequestTraining::dispatch($record, auth()->id());

                    $record->status = ModelStatus::TRAINING;
                    $record->save();

                    Notification::make()
                        ->info()
                        ->title('Requested Training')
                        ->body('We will notify you once the model is ready!')
                        ->send();
                })
                ->visible(function ($record) {
                    return $record->status === ModelStatus::SUBMITTED || $record->status === ModelStatus::TRAINING;
                })
                // ->disabled(function ($record) {
                //     return $record->status === ModelStatus::TRAINING;
                // })
                ->color('info')
                ->icon('heroicon-m-light-bulb'),

            Action::make('Execute')
                ->form([
                    FileUpload::make('image')
                        ->imageEditor()
                        ->required()
                        ->image()
                ])
                ->action(function ($record, array $data): void {
                    $data['userId'] = auth()->id();

                    RequestTest::dispatch($record, $data);

                    Notification::make()
                        ->info()
                        ->title('Requested Test')
                        ->body('We will notify you once the result are here!')
                        ->send();
                })
                ->visible(function ($record) {
                    return $record->status === ModelStatus::READY;
                })
                ->color('success')
                ->icon('heroicon-o-play')
        ];
    }
}
