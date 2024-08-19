<?php

namespace App\Filament\Resources\Events\Versions\Scoring;

use App\Filament\Resources\Events\Versions\Scoring\ScoreFactorResource\Pages;
use App\Models\Events\Versions\Scoring\ScoreFactor;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ScoreFactorResource extends Resource
{
    protected static ?string $model = ScoreFactor::class;

    protected static ?string $slug = 'events/versions/scoring/score-factors';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Placeholder::make('created_at')
                    ->label('Created Date')
                    ->content(fn(?ScoreFactor $record): string => $record?->created_at?->diffForHumans() ?? '-'),

                Placeholder::make('updated_at')
                    ->label('Last Modified Date')
                    ->content(fn(?ScoreFactor $record): string => $record?->updated_at?->diffForHumans() ?? '-'),

                Select::make('event_id')
                    ->relationship('event', 'name')
                    ->searchable()
                    ->required(),

                Select::make('version_id')
                    ->relationship('version', 'name')
                    ->searchable(),

                TextInput::make('score_category_id')
                    ->required()
                    ->integer(),

                TextInput::make('factor')
                    ->required(),

                TextInput::make('abbr')
                    ->required(),

                TextInput::make('best')
                    ->required()
                    ->integer(),

                TextInput::make('worst')
                    ->required()
                    ->integer(),

                TextInput::make('interval_by')
                    ->required()
                    ->integer(),

                TextInput::make('multiplier')
                    ->required()
                    ->integer(),

                TextInput::make('tolerance')
                    ->required()
                    ->integer(),

                TextInput::make('order_by')
                    ->required()
                    ->integer(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('event.name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('version.name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('score_category_id'),

                TextColumn::make('factor'),

                TextColumn::make('abbr'),

                TextColumn::make('best'),

                TextColumn::make('worst'),

                TextColumn::make('interval_by'),

                TextColumn::make('multiplier'),

                TextColumn::make('tolerance'),

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
            'index' => Pages\ListScoreFactors::route('/'),
            'create' => Pages\CreateScoreFactor::route('/create'),
            'edit' => Pages\EditScoreFactor::route('/{record}/edit'),
        ];
    }

    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()->with(['event', 'version']);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['event.name', 'version.name'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        $details = [];

        if ($record->event) {
            $details['Event'] = $record->event->name;
        }

        if ($record->version) {
            $details['Version'] = $record->version->name;
        }

        return $details;
    }
}
