<?php

namespace App\Filament\Resources\Events\Versions\Participations;

use App\Filament\Resources\Events\Versions\Participations\AuditionResultResource\Pages;
use App\Models\Events\Versions\Participations\AuditionResult;
use Filament\Forms\Components\Checkbox;
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

class AuditionResultResource extends Resource
{
    protected static ?string $model = AuditionResult::class;

    protected static ?string $slug = 'events/versions/participations/audition-results';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Placeholder::make('created_at')
                    ->label('Created Date')
                    ->content(fn(?AuditionResult $record): string => $record?->created_at?->diffForHumans() ?? '-'),

                Placeholder::make('updated_at')
                    ->label('Last Modified Date')
                    ->content(fn(?AuditionResult $record): string => $record?->updated_at?->diffForHumans() ?? '-'),

                TextInput::make('candidate_id')
                    ->required()
                    ->integer(),

                Select::make('version_id')
                    ->relationship('version', 'name')
                    ->searchable()
                    ->required(),

                TextInput::make('voice_part_id')
                    ->required()
                    ->integer(),

                Select::make('school_id')
                    ->relationship('school', 'name')
                    ->searchable()
                    ->required(),

                TextInput::make('voice_part_order_by')
                    ->required()
                    ->integer(),

                TextInput::make('score_count')
                    ->required()
                    ->integer(),

                TextInput::make('total')
                    ->required()
                    ->integer(),

                Checkbox::make('accepted'),

                TextInput::make('acceptance_abbr')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('candidate_id'),

                TextColumn::make('version.name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('voice_part_id'),

                TextColumn::make('school.name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('voice_part_order_by'),

                TextColumn::make('score_count'),

                TextColumn::make('total'),

                TextColumn::make('accepted'),

                TextColumn::make('acceptance_abbr'),
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
            'index' => Pages\ListAuditionResults::route('/'),
            'create' => Pages\CreateAuditionResult::route('/create'),
            'edit' => Pages\EditAuditionResult::route('/{record}/edit'),
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
