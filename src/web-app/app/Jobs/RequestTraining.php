<?php

namespace App\Jobs;

use App\Models\IAModel;
use App\Enums\ModelStatus;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use App\Services\ModelTraining\Requests\TestModelRequest;

class RequestTraining implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public IAModel $record,
        public int $userId,
    ) {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $client = app()->make(TestModelRequest::class);

        $client->attach(
            'zip_file',
            file_get_contents(storage_path('app/public/' . $this->record->dataset?->path)),
            'archive.zip'
        )->send([
            "ratio" => (float) $this->record->ration,
            "epochs" => (int) $this->record->training_epochs,
            "batch_size" => (int) $this->record->batch_size,
            "shuffle" => (bool) $this->record->shuffle,
            "userId" => $this->userId,
            "modelId" => $this->record->id
        ]);
    }
}
