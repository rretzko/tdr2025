<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ViewPageResource\Pages;
use App\Filament\Resources\ViewPageResource\RelationManagers;
use App\Models\ViewPage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ViewPageResource extends Resource
{
    protected static ?string $model = ViewPage::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('controller')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('method')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('page_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('header')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('controller'),
                Tables\Columns\TextColumn::make('method'),
                Tables\Columns\TextColumn::make('page_name'),
                Tables\Columns\TextColumn::make('header'),
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
            'index' => Pages\ListViewPages::route('/'),
            'create' => Pages\CreateViewPage::route('/create'),
            'edit' => Pages\EditViewPage::route('/{record}/edit'),
        ];
    }
}
