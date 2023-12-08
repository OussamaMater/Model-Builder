<?php

namespace App\Models;

use Filament\Forms\Components\Section;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Dataset extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'path',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
    ];

    public function models(): HasMany
    {
        return $this->hasMany(\App\Models\IAModel::class);
    }

    public static function getForm(): array
    {
        return [
            Section::make('Dataset Details')
                ->icon('heroicon-o-information-circle')
                ->description('General information about your dataset.')
                ->schema([
                    TextInput::make('name')
                        ->required(),
                    MarkdownEditor::make('description'),
                    FileUpload::make('path')
                        ->label('Dataset')
                        ->required()
                        ->downloadable()
                ])

        ];
    }
}
