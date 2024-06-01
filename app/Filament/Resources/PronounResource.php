<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PronounResource\Pages;
use App\Models\Pronoun;
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

class PronounResource extends Resource
{
    protected static ?string $model = Pronoun::class;

    protected static ?string $slug = 'pronouns';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Placeholder::make('created_at')
                    ->label('Created Date')
                    ->content(fn(?Pronoun $record): string => $record?->created_at?->diffForHumans() ?? '-'),

                Placeholder::make('updated_at')
                    ->label('Last Modified Date')
                    ->content(fn(?Pronoun $record): string => $record?->updated_at?->diffForHumans() ?? '-'),

                TextInput::make('descr')
                    ->required(),

                TextInput::make('intensive')
                    ->required()
                    ->integer(),

                TextInput::make('personal')
                    ->required(),

                TextInput::make('possessive')
                    ->required(),

                TextInput::make('object')
                    ->required(),

                TextInput::make('order_by')
                    ->required()
                    ->integer(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('descr'),

                TextColumn::make('intensive'),

                TextColumn::make('personal'),

                TextColumn::make('possessive'),

                TextColumn::make('object'),

                TextColumn::make('order_by'),
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
            'index' => Pages\ListPronouns::route('/'),
            'create' => Pages\CreatePronoun::route('/create'),
            'edit' => Pages\EditPronoun::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [];
    }
}
