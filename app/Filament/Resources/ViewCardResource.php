<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ViewCardResource\Pages;
use App\Filament\Resources\ViewCardResource\RelationManagers;
use App\Models\ViewCard;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ViewCardResource extends Resource
{
    protected static ?string $model = ViewCard::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('color')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('description')
                    ->required(),
                Forms\Components\TextInput::make('header')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('heroicon')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('href')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('label')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('order_by')
                    ->options([
                        '1' => '1',
                        '2' => '2',
                        '3' => '3',
                        '4' => '4',
                        '5' => '5',
//                        '6' => '6',
//                        '7' => '7',
//                        '8' => '8',
//                        '9' => '9',
//                        '10' => '10',
//                        '11' => '11',
//                        '13' => '13',
//                        '14' => '14',
//                        '15' => '15',
//                        '16' => '16',
//                        '17' => '17',
//                        '18' => '18',
//                        '19' => '19',
//                        '20' => '20',
                    ])
                    ->required(),


            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_by'),
                Tables\Columns\TextColumn::make('header'),
                Tables\Columns\TextColumn::make('heroicon'),
                Tables\Columns\TextColumn::make('color'),
                Tables\Columns\TextColumn::make('href'),
                Tables\Columns\TextColumn::make('label'),
                Tables\Columns\TextColumn::make('description'),
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
            'index' => Pages\ListViewCards::route('/'),
            'create' => Pages\CreateViewCard::route('/create'),
            'edit' => Pages\EditViewCard::route('/{record}/edit'),
        ];
    }
}
