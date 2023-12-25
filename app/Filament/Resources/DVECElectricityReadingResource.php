<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DVECElectricityReadingResource\Pages;
use App\Filament\Resources\DVECElectricityReadingResource\RelationManagers;
use App\Models\DVECElectricityReading;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;

class DVECElectricityReadingResource extends Resource
{
    protected static ?string $model = DVECElectricityReading::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('transmit_date'),
                Forms\Components\TextInput::make('previous_readings')
                    ->numeric()
                    ->mask('99999'),
                Forms\Components\TextInput::make('new_readings')
                    ->numeric()
                    ->mask('99999'),
                Forms\Components\TextInput::make('difference')
                    ->numeric(),
                Forms\Components\Textarea::make('comment')
                    ->maxLength(65535)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('transmit_date')
                    ->date('Y.m')
                    ->sortable(),
                Tables\Columns\TextColumn::make('previous_readings')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('new_readings')
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
            'index' => Pages\ListDVECElectricityReadings::route('/'),
            'create' => Pages\CreateDVECElectricityReading::route('/create'),
            'edit' => Pages\EditDVECElectricityReading::route('/{record}/edit'),
        ];
    }
}
