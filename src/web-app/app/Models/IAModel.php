<?php

namespace App\Models;

use App\Models\Dataset;
use App\Enums\ModelStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Illuminate\Database\Eloquent\Relations\HasMany;

class IAModel extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'avatar',
        'ration',
        'batch_size',
        'shuffle',
        'training_epochs',
        'dataset_id',
        'description',
        'status',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'ration' => 'float',
        'shuffle' => 'boolean',
        'dataset_id' => 'integer',
        'status' => ModelStatus::class,
    ];

    public function dataset(): BelongsTo
    {
        return $this->belongsTo(Dataset::class);
    }

    public function histories(): HasMany
    {
        return $this->hasMany(History::class, 'i_a_model_id', 'id');
    }

    public static function getForm(): array
    {
        return [
            Section::make('Model Details')
                ->icon('heroicon-o-information-circle')
                ->description('General information about your model.')
                ->collapsible()
                ->schema(
                    [
                        TextInput::make('name')
                            ->required(),
                        MarkdownEditor::make('description'),
                        FileUpload::make('avatar')
                            ->imageEditor()
                            ->image()
                    ]
                ),

            Section::make('Model Configuration')
                ->icon('heroicon-o-cog-6-tooth')
                ->description('Configure your model.')
                ->collapsible()
                ->schema(
                    [
                        TextInput::make('ration')
                            ->helperText('Ratio used within the model.')
                            ->label('Ratio')
                            ->required()
                            ->numeric()
                            ->placeholder(2)
                            ->default(2),
                        TextInput::make('batch_size')
                            ->helperText('Size of batches used for model training.')
                            ->required()
                            ->numeric()
                            ->placeholder(32)
                            ->default(32),
                        TextInput::make('training_epochs')
                            ->helperText('Number of epochs for training the model')
                            ->required()
                            ->numeric()
                            ->placeholder(5)
                            ->default(5),
                        Toggle::make('shuffle')
                            ->helperText('Boolean indicating whether the data should be shuffled during training or not')
                            ->required(),
                    ]
                ),

            Section::make('Related Dataset')
                ->icon('heroicon-o-server-stack')
                ->collapsible()
                ->description('Pick the suitable dataset for your model.')
                ->schema([
                    Select::make('dataset_id')
                        ->createOptionForm(Dataset::getForm())
                        ->relationship('dataset', 'name')
                        ->columnSpanFull()
                        ->native(false)
                        ->required(),
                ])
        ];
    }
}
