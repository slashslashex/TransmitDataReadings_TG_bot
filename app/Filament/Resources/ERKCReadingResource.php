<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ERKCReadingResource\Pages;
use App\Filament\Resources\ERKCReadingResource\RelationManagers;
use App\Models\ERKCReading;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ERKCReadingResource extends Resource
{
    protected static ?string $model = ERKCReading::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('cold water previous readings')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('cold water new readings')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('hot water previous readings')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('hot water new readings')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('difference')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('cold water previous readings')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('cold water new readings')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('hot water previous readings')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('hot water new readings')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('difference')
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
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListERKCReadings::route('/'),
            'create' => Pages\CreateERKCReading::route('/create'),
            'edit' => Pages\EditERKCReading::route('/{record}/edit'),
        ];
    }
}
