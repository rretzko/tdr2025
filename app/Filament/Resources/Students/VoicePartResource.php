<?php

namespace App\Filament\Resources\Students;

use App\Filament\Resources\Students\VoicePartResource\Pages;
use App\Models\Students\VoicePart;
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

class VoicePartResource extends Resource
{
    protected static ?string $model = VoicePart::class;

    protected static ?string $slug = 'students/voice-parts';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Placeholder::make('created_at')
                    ->label('Created Date')
                    ->content(fn(?VoicePart $record): string => $record?->created_at?->diffForHumans() ?? '-'),

                Placeholder::make('updated_at')
                    ->label('Last Modified Date')
                    ->content(fn(?VoicePart $record): string => $record?->updated_at?->diffForHumans() ?? '-'),

                TextInput::make('descr')
                    ->required(),

                TextInput::make('abbr')
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

                TextColumn::make('abbr'),

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
            'index' => Pages\ListVoiceParts::route('/'),
            'create' => Pages\CreateVoicePart::route('/create'),
            'edit' => Pages\EditVoicePart::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [];
    }
}