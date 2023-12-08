<?php

use App\Models\User;
use App\Models\IAModel;
use App\Enums\ModelStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action;
use App\Filament\Resources\IAModelResource;

Route::get('model-ready/{id}', function (Request $request, int $id) {
    $model = IAModel::findOrFail($id);

    $model->status = ModelStatus::READY;
    $model->save();

    $notification = Notification::make()
        ->title('Your model is ready!')
        ->body('You can start testing your model now.')
        ->actions([
            Action::make('check')
                ->button()
                ->color('primary')
                ->url(IAModelResource::getUrl('view', ['record' => $id]))
        ])
        ->sendToDatabase(User::first());
});

Route::post('results-ready/{id}', function (Request $request, int $id) {
    $model = IAModel::findOrFail($id);

    Log::info($request->all());

    $model->histories()->create([
        'related_image' => $request->get('related_image'), // path
        'i_a_model_id' => $id,
        'created_at' => $request->get('started_at')
    ]);

    Storage::disk('public')->put($request->get('related_image'), file_get_contents($request->file('file')));
    
    Notification::make()
        ->title('Your results are here!')
        ->body('You can consult the results on your model.')
        ->actions([
            Action::make('check')
                ->button()
                ->color('primary')
                ->url(IAModelResource::getUrl('view', ['record' => $id]))
        ])
        ->sendToDatabase(User::findOrFail($request->get('user_id')));
});
