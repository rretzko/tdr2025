<?php

namespace App\Filament\Resources\Events\Versions\Scoring;

use App\Filament\Resources\Events\Versions\Scoring\RoomScoreCategoriesResource\Pages;
use App\Models\Events\Versions\Scoring\RoomScoreCategory;
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

class RoomScoreCategoriesResource extends Resource
{
    protected static ?string $model = RoomScoreCategory::class;

    protected static ?string $slug = 'events/versions/scoring/room-score-categories';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Placeholder::make('created_at')
                    ->label('Created Date')
                    ->content(fn(?RoomScoreCategory $record
                    ): string => $record?->created_at?->diffForHumans() ?? '-'),

                Placeholder::make('updated_at')
                    ->label('Last Modified Date')
                    ->content(fn(?RoomScoreCategory $record
                    ): string => $record?->updated_at?->diffForHumans() ?? '-'),

                TextInput::make('room_id')
                    ->required()
                    ->integer(),

                TextInput::make('score_category_id')
                    ->required()
                    ->integer(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('room_id'),

                TextColumn::make('score_category_id'),
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
            'index' => Pages\ListRoomScoreCategories::route('/'),
            'create' => Pages\CreateRoomScoreCategories::route('/create'),
            'edit' => Pages\EditRoomScoreCategories::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [];
    }
}
