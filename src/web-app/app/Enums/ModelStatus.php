<?php

namespace App\Enums;

enum ModelStatus: string
{
    case SUBMITTED = 'Submitted';
    case TRAINING = 'Training';
    case READY = 'Ready';

    public function getColor(): string
    {
        return match ($this) {
            self::SUBMITTED => 'primary',
            self::TRAINING => 'warning',
            self::READY => 'success',
        };
    }
}