<?php

namespace App\Filament\Ruler\Resources;

use App\Filament\Ruler\Resources\RulerResource\Pages;
use App\Filament\Ruler\Resources\RulerResource\RelationManagers;
use App\Models\Ruler;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RulerResource extends Resource
{
    protected static ?string $model = Ruler::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
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
            'index' => Pages\ListRulers::route('/'),
            'create' => Pages\CreateRuler::route('/create'),
            'edit' => Pages\EditRuler::route('/{record}/edit'),
        ];
    }
}
