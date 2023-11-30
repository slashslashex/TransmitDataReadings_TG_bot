<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DVECReadingResource\Pages;
use App\Filament\Resources\DVECReadingResource\RelationManagers;
use App\Models\DVECReading;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DVECReadingResource extends Resource
{
    protected static ?string $model = DVECReading::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('previous readings')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('new readings')
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
                Tables\Columns\TextColumn::make('previous readings')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('new readings')
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
            'index' => Pages\ListDVECReadings::route('/'),
            'create' => Pages\CreateDVECReading::route('/create'),
            'edit' => Pages\EditDVECReading::route('/{record}/edit'),
        ];
    }
}
