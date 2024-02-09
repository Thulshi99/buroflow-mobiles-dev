<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QntrlCardResource\Pages;
use App\Filament\Resources\QntrlCardResource\RelationManagers;
use App\Models\Qntrl;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class QntrlCardResource extends Resource
{
    protected static ?string $model = Qntrl::class;

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
                TextColumn::make(name: 'title')->translateLabel(),
                TextColumn::make(name: 'description')->translateLabel(),
                TextColumn::make(name: 'duedate')->label('Due Date'),
                TextColumn::make(name: 'priority')->translateLabel(),
                TextColumn::make(name: 'loc_id')->label('LOC ID'),
                TextColumn::make(name: 'cpi')->label('CPI'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListQntrlCards::route('/'),
            'create' => Pages\CreateQntrlCard::route('/create'),
            'edit' => Pages\EditQntrlCard::route('/{record}/edit'),
        ];
    }
}
