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
                Forms\Components\DatePicker::make('transmit_date'),
                Forms\Components\TextInput::make('cold_water_previous_readings')
                    ->numeric()
                    ->mask('99999.999'),
                Forms\Components\TextInput::make('cold_water_new_readings')
                    ->numeric()
                    ->mask('99999.999'),
                Forms\Components\TextInput::make('hot_water_previous_readings')
                    ->numeric()
                    ->mask('99999.999'),
                Forms\Components\TextInput::make('hot_water_new_readings')
                    ->numeric()
                    ->mask('99999.999'),
                Forms\Components\TextInput::make('cold_water_difference')
                    ->numeric()
                    ->mask('99999.999'),
                Forms\Components\TextInput::make('hot_water_difference')
                    ->numeric()
                    ->mask('99999.999'),
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
                Tables\Columns\TextColumn::make('cold_water_previous_readings')
                    ->numeric('3', '.')
                    ->sortable(),
                Tables\Columns\TextColumn::make('cold_water_new_readings')
                    ->numeric('3', '.')
                    ->sortable(),
                Tables\Columns\TextColumn::make('hot_water_previous_readings')
                    ->numeric('3', '.')
                    ->sortable(),
                Tables\Columns\TextColumn::make('hot_water_new_readings')
                    ->numeric('3', '.')
                    ->sortable(),
                Tables\Columns\TextColumn::make('cold_water_difference')
                    ->numeric('3', '.')
                    ->sortable(),
                Tables\Columns\TextColumn::make('hot_water_difference')
                    ->numeric('3', '.')
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
