<?php

namespace App\Filament\Resources\Events;

use App\Filament\Resources\Events\EventEnsembleResource\Pages;
use App\Models\Events\EventEnsemble;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ForceDeleteAction;
use Filament\Tables\Actions\ForceDeleteBulkAction;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EventEnsembleResource extends Resource
{
    protected static ?string $model = EventEnsemble::class;

    protected static ?string $slug = 'events/event-ensembles';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Placeholder::make('created_at')
                    ->label('Created Date')
                    ->content(fn(?EventEnsemble $record): string => $record?->created_at?->diffForHumans() ?? '-'),

                Placeholder::make('updated_at')
                    ->label('Last Modified Date')
                    ->content(fn(?EventEnsemble $record): string => $record?->updated_at?->diffForHumans() ?? '-'),

                Select::make('event_id')
                    ->relationship('event', 'name')
                    ->searchable()
                    ->required(),

                TextInput::make('ensemble_name')
                    ->required(),

                TextInput::make('ensemble_short_name')
                    ->required(),

                TextInput::make('grades')
                    ->required(),

                TextInput::make('voice_part_ids')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('event.name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('ensemble_name'),

                TextColumn::make('ensemble_short_name'),

                TextColumn::make('grades'),

                TextColumn::make('voice_part_ids'),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
                RestoreAction::make(),
                ForceDeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEventEnsembles::route('/'),
            'create' => Pages\CreateEventEnsemble::route('/create'),
            'edit' => Pages\EditEventEnsemble::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()->with(['event']);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['event.name'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        $details = [];

        if ($record->event) {
            $details['Event'] = $record->event->name;
        }

        return $details;
    }
}
