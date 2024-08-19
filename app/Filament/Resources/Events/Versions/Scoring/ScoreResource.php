<?php

namespace App\Filament\Resources\Events\Versions\Scoring;

use App\Filament\Resources\Events\Versions\Scoring\ScoreResource\Pages;
use App\Models\Events\Versions\Scoring\Score;
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

class ScoreResource extends Resource
{
    protected static ?string $model = Score::class;

    protected static ?string $slug = 'events/versions/scoring/scores';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Placeholder::make('created_at')
                    ->label('Created Date')
                    ->content(fn(?Score $record): string => $record?->created_at?->diffForHumans() ?? '-'),

                Placeholder::make('updated_at')
                    ->label('Last Modified Date')
                    ->content(fn(?Score $record): string => $record?->updated_at?->diffForHumans() ?? '-'),

                Select::make('version_id')
                    ->relationship('version', 'name')
                    ->searchable()
                    ->required(),

                TextInput::make('candidate_id')
                    ->required()
                    ->integer(),

                TextInput::make('student_id')
                    ->required()
                    ->integer(),

                Select::make('school_id')
                    ->relationship('school', 'name')
                    ->searchable()
                    ->required(),

                TextInput::make('score_category_id')
                    ->required()
                    ->integer(),

                TextInput::make('score_category_order_by')
                    ->required()
                    ->integer(),

                TextInput::make('score_factor_id')
                    ->required()
                    ->integer(),

                TextInput::make('score_factor_order_by')
                    ->required()
                    ->integer(),

                TextInput::make('judge_id')
                    ->required()
                    ->integer(),

                TextInput::make('judge_order_by')
                    ->required()
                    ->integer(),

                TextInput::make('voice_part_id')
                    ->required()
                    ->integer(),

                TextInput::make('voice_part_order_by')
                    ->required()
                    ->integer(),

                TextInput::make('score')
                    ->required()
                    ->integer(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('version.name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('candidate_id'),

                TextColumn::make('student_id'),

                TextColumn::make('school.name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('score_category_id'),

                TextColumn::make('score_category_order_by'),

                TextColumn::make('score_factor_id'),

                TextColumn::make('score_factor_order_by'),

                TextColumn::make('judge_id'),

                TextColumn::make('judge_order_by'),

                TextColumn::make('voice_part_id'),

                TextColumn::make('voice_part_order_by'),

                TextColumn::make('score'),
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
            'index' => Pages\ListScores::route('/'),
            'create' => Pages\CreateScore::route('/create'),
            'edit' => Pages\EditScore::route('/{record}/edit'),
        ];
    }

    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()->with(['version', 'school']);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['version.name', 'school.name'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        $details = [];

        if ($record->version) {
            $details['Version'] = $record->version->name;
        }

        if ($record->school) {
            $details['School'] = $record->school->name;
        }

        return $details;
    }
}
