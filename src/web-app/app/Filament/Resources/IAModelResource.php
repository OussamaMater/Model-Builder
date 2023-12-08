<?php

namespace App\Filament\Resources;

use Filament\Tables;
use App\Models\IAModel;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Infolists\Components\Group;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use App\Filament\Resources\IAModelResource\Pages;
use Filament\Infolists\Components\Section as ComponentsSection;
use App\Filament\Resources\IAModelResource\RelationManagers\HistoriesRelationManager;

class IAModelResource extends Resource
{
    protected static ?string $model = IAModel::class;

    protected static ?string $modelLabel = 'Training Model';

    protected static ?string $navigationIcon = 'heroicon-o-cpu-chip';

    protected static ?string $recordTitleAttribute = 'name';


    public static function form(Form $form): Form
    {
        return $form
            ->schema(IAModel::getForm());
    }

    public static function table(Table $table): Table
    {
        return $table
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
                    ->label('Ratio')
                    ->sortable(),
                Tables\Columns\TextColumn::make('batch_size')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('shuffle')
                    ->boolean(),
                Tables\Columns\TextColumn::make('training_epochs')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('dataset.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->sortable()
                    ->color(function ($state) {
                        return $state->getColor();
                    }),
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
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'submitted' => 'Submitted',
                        'training' => 'Training',
                        'ready' => 'Ready',
                    ])
                    ->attribute('status')
                    ->multiple()
                    ->searchable()
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                ComponentsSection::make('Model Overview')
                    ->columns(3)
                    ->schema([
                        ImageEntry::make('avatar')
                            ->circular()
                            ->defaultImageUrl(function ($record) {
                                return 'https://www.gravatar.com/robohash/' . urlencode($record->name);
                            }),
                        Group::make()
                            ->columnSpan(2)
                            ->columns(2)
                            ->schema([
                                TextEntry::make('name'),
                                TextEntry::make('ration')->label('Ratio'),
                                TextEntry::make('batch_size'),
                                TextEntry::make('training_epochs'),
                                IconEntry::make('shuffle'),
                                TextEntry::make('status')
                                    ->badge()
                                    ->color(function ($state) {
                                        return $state->getColor();
                                    }),
                            ]),
                    ]),
                ComponentsSection::make('Model Description')
                    ->collapsible()
                    ->hidden(function ($record) {
                        return empty($record->description);
                    })
                    ->schema([
                        TextEntry::make('description')
                            ->hiddenLabel()
                            ->markdown(),
                    ])
            ]);
    }

    public static function getRelations(): array
    {
        return [
            HistoriesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListIAModels::route('/'),
            'create' => Pages\CreateIAModel::route('/create'),
            'view' => Pages\ViewModel::route('/{record}'),
        ];
    }
}
