<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class History extends Model
{
    use HasFactory;

    protected $fillable = [
        'related_image',
        'results',
        'i_a_model_id',
    ];

    protected $casts = [
        'results' => 'array',
    ];

    public function iaModel(): BelongsTo
    {
        return $this->belongsTo(IAModel::class, 'i_a_model_id', 'id');
    }
}
