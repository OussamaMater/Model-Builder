<?php

namespace App\Services\ModelTraining\Requests;

use Illuminate\Http\Client\PendingRequest;

abstract class Request
{
    protected string $uri;

    public function __construct(
        protected readonly PendingRequest $client,
    ) {
        $this->uri = config('services.model_training.url');
    }

    protected function url(string $path): string
    {
        return "{$this->uri}/$path";
    }

    public function withBody(array $params = []): self
    {
        $this->client->with($params);

        return $this;
    }

    public function attach(string $name, string $contents, string $filename): self
    {
        $this->client->attach($name, $contents, $filename);

        return $this;
    }
}
