<?php

namespace App\Filament\Resources\DatasetResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section as ComponentsSection;
use Filament\Infolists\Components\ImageEntry;



class ModelsRelationManager extends RelationManager
{
    protected static string $relationship = 'models';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Model Details')
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
                    ->schema(
                        [
                            TextInput::make('ration')
                                ->hint('Ratio of model')
                                ->required()
                                ->numeric()
                                ->placeholder(2)
                                ->default(2),
                            TextInput::make('batch_size')
                                ->hint('Batch of model')
                                ->required()
                                ->numeric()
                                ->placeholder(32)
                                ->default(32),
                            TextInput::make('training_epochs')
                                ->hint('Training stuff')
                                ->required()
                                ->numeric()
                                ->placeholder(5)
                                ->default(5),
                            Toggle::make('shuffle')
                                ->hint('Should the model shuffle or not')
                                ->required(),
                        ]
                    ),
                Section::make('Related Dataset')
                    ->schema([
                        Forms\Components\Select::make('dataset_id')
                            ->relationship('dataset', 'name')
                            ->columnSpanFull()
                            ->required(),
                    ])
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('avatar')
                    ->circular()
                    ->defaultImageUrl(function ($record) {
                        return 'https://www.gravatar.com/robohash/' . urlencode($record->name);
                    }),
                Tables\Columns\TextColumn::make('ration')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('batch_size')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('shuffle')
                    ->boolean(),
                Tables\Columns\TextColumn::make('training_epochs')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->label('New Model'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
