<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GeostateResource\Pages;
use App\Models\Geostate;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class GeostateResource extends Resource
{
    protected static ?string $model = Geostate::class;

    protected static ?string $slug = 'geostates';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Placeholder::make('created_at')
                    ->label('Created Date')
                    ->content(fn(?Geostate $record): string => $record?->created_at?->diffForHumans() ?? '-'),

                Placeholder::make('updated_at')
                    ->label('Last Modified Date')
                    ->content(fn(?Geostate $record): string => $record?->updated_at?->diffForHumans() ?? '-'),

                TextInput::make('country_abbr')
                    ->required(),

                TextInput::make('name')
                    ->required(),

                TextInput::make('abbr')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('country_abbr'),

                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('abbr'),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGeostates::route('/'),
            'create' => Pages\CreateGeostate::route('/create'),
            'edit' => Pages\EditGeostate::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name'];
    }
}
