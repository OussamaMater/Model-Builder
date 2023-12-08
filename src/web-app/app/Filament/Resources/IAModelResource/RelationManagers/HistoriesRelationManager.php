<?php

namespace App\Filament\Resources\IAModelResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Illuminate\Support\HtmlString;
use Filament\Infolists\Components\Entry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Resources\RelationManagers\RelationManager;

class HistoriesRelationManager extends RelationManager
{
    protected static string $relationship = 'histories';

    public function isReadOnly(): bool
    {
        return false;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('id')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Requested At')
                    ->sortable()
                    ->date(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Received At')
                    ->sortable()
                    ->date(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->hidden(function ($record) {
                        return is_null($record->related_image);
                    }),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('You have not tested the model yet.')
            ->poll('10s');
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                ImageEntry::make('related_image')
                    ->hiddenLabel()
                    ->columnSpanFull()
                    ->height('800')
                    ->width('673')
            ]);
    }
}
