<?php

namespace App\Services\ModelTraining\DTO;

class TestModel
{
    public function __construct(
        public readonly string $result,
        public readonly array $matrix,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            result: $data['result'],
            matrix: $data['matrix'],
        );
    }
}
