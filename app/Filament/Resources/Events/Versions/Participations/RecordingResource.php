<?php

namespace App\Filament\Resources\Events\Versions\Participations;

use App\Filament\Resources\Events\Versions\Participations\RecordingResource\Pages;
use App\Models\Events\Versions\Participations\Recording;
use Filament\Forms\Components\DatePicker;
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

class RecordingResource extends Resource
{
    protected static ?string $model = Recording::class;

    protected static ?string $slug = 'events/versions/participations/recordings';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Placeholder::make('created_at')
                    ->label('Created Date')
                    ->content(fn(?Recording $record): string => $record?->created_at?->diffForHumans() ?? '-'),

                Placeholder::make('updated_at')
                    ->label('Last Modified Date')
                    ->content(fn(?Recording $record): string => $record?->updated_at?->diffForHumans() ?? '-'),

                Select::make('version_id')
                    ->relationship('version', 'name')
                    ->searchable()
                    ->required(),

                TextInput::make('candidate_id')
                    ->required()
                    ->integer(),

                TextInput::make('file_type')
                    ->required(),

                TextInput::make('uploaded_by')
                    ->required()
                    ->integer(),

                DatePicker::make('approved'),

                TextInput::make('url')
                    ->required()
                    ->url(),
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

                TextColumn::make('file_type'),

                TextColumn::make('uploaded_by'),

                TextColumn::make('approved')
                    ->date(),

                TextColumn::make('url'),
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
            'index' => Pages\ListRecordings::route('/'),
            'create' => Pages\CreateRecording::route('/create'),
            'edit' => Pages\EditRecording::route('/{record}/edit'),
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
