<?php

namespace App\Services\ModelTraining\Requests;

use App\Services\ModelTraining\Requests\Request;

class TestModelRequest extends Request
{
    public function send(array $payload = [], string $path = 'upload-zip')
    {
        $data = $this->client
            ->timeout(1200)
            ->post($this->url($path), $payload)
            ->throw();

        return $data;
    }
}
