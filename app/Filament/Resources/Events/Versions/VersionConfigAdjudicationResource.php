<?php

namespace App\Filament\Resources\Events\Versions;

use App\Filament\Resources\Events\Versions\VersionConfigAdjudicationResource\Pages;
use App\Models\Events\Versions\VersionConfigAdjudication;
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

class VersionConfigAdjudicationResource extends Resource
{
    protected static ?string $model = VersionConfigAdjudication::class;

    protected static ?string $slug = 'events/versions/version-config-adjudications';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Placeholder::make('created_at')
                    ->label('Created Date')
                    ->content(fn(?VersionConfigAdjudication $record
                    ): string => $record?->created_at?->diffForHumans() ?? '-'),

                Placeholder::make('updated_at')
                    ->label('Last Modified Date')
                    ->content(fn(?VersionConfigAdjudication $record
                    ): string => $record?->updated_at?->diffForHumans() ?? '-'),

                Select::make('version_id')
                    ->relationship('version', 'name')
                    ->searchable()
                    ->required(),

                TextInput::make('upload_count')
                    ->required()
                    ->integer(),

                TextInput::make('upload_types')
                    ->required(),

                TextInput::make('judge_per_room_count')
                    ->required()
                    ->integer(),

                Checkbox::make('room_monitor'),

                Checkbox::make('averaged_scores'),

                Checkbox::make('scores_ascending'),

                Checkbox::make('alternating_scores'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('version.name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('upload_count'),

                TextColumn::make('upload_types'),

                TextColumn::make('judge_per_room_count'),

                TextColumn::make('room_monitor'),

                TextColumn::make('averaged_scores'),

                TextColumn::make('scores_ascending'),

                TextColumn::make('alternating_scores'),
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
            'index' => Pages\ListVersionConfigAdjudications::route('/'),
            'create' => Pages\CreateVersionConfigAdjudication::route('/create'),
            'edit' => Pages\EditVersionConfigAdjudication::route('/{record}/edit'),
        ];
    }

    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()->with(['version']);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['version.name'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        $details = [];

        if ($record->version) {
            $details['Version'] = $record->version->name;
        }

        return $details;
    }
}
