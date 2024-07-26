<?php

namespace App\Filament\Resources\Events\Versions\Participations;

use App\Filament\Resources\Events\Versions\Participations\CandidateResource\Pages;
use App\Models\Events\Versions\Participations\Candidate;
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

class CandidateResource extends Resource
{
    protected static ?string $model = Candidate::class;

    protected static ?string $slug = 'events/versions/participations/candidates';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Placeholder::make('created_at')
                    ->label('Created Date')
                    ->content(fn(?Candidate $record): string => $record?->created_at?->diffForHumans() ?? '-'),

                Placeholder::make('updated_at')
                    ->label('Last Modified Date')
                    ->content(fn(?Candidate $record): string => $record?->updated_at?->diffForHumans() ?? '-'),

                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->required(),

                Select::make('version_id')
                    ->relationship('version', 'name')
                    ->searchable()
                    ->required(),

                TextInput::make('student_id')
                    ->required()
                    ->integer(),

                TextInput::make('teacher_id')
                    ->required()
                    ->integer(),

                TextInput::make('program_name')
                    ->required(),

                TextInput::make('candidate_type')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('version.name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('student_id'),

                TextColumn::make('teacher_id'),

                TextColumn::make('program_name'),

                TextColumn::make('candidate_type'),
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
            'index' => Pages\ListCandidates::route('/'),
            'create' => Pages\CreateCandidate::route('/create'),
            'edit' => Pages\EditCandidate::route('/{record}/edit'),
        ];
    }

    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()->with(['user', 'version']);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['user.name', 'version.name'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        $details = [];

        if ($record->user) {
            $details['User'] = $record->user->name;
        }

        if ($record->version) {
            $details['Version'] = $record->version->name;
        }

        return $details;
    }
}
