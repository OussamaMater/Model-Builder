<?php

namespace App\Jobs;

use App\Models\IAModel;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Services\ModelTraining\Requests\TestModelRequest;

class RequestTest implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public IAModel $record,
        public array $data
    ) {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $client = app()->make(TestModelRequest::class);

        $client->attach(
            'image',
            file_get_contents(storage_path('app/public/' . $this->data['image'])),
            $this->data['image']
        )->send([
            "batch_size" => (int) $this->record->batch_size,
            "userId" => $this->data['userId'],
            "modelId" => $this->record->id
        ], 'train-model');
    }
}
