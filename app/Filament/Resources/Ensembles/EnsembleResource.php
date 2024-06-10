<?php

namespace App\Filament\Resources\Ensembles;

use App\Filament\Resources\Ensembles\EnsembleResource\Pages;
use App\Models\Ensembles\Ensemble;
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

class EnsembleResource extends Resource
{
    protected static ?string $model = Ensemble::class;

    protected static ?string $slug = 'ensembles/ensembles';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Placeholder::make('created_at')
                    ->label('Created Date')
                    ->content(fn(?Ensemble $record): string => $record?->created_at?->diffForHumans() ?? '-'),

                Placeholder::make('updated_at')
                    ->label('Last Modified Date')
                    ->content(fn(?Ensemble $record): string => $record?->updated_at?->diffForHumans() ?? '-'),

                Select::make('school_id')
                    ->relationship('school', 'name')
                    ->searchable()
                    ->required(),

                TextInput::make('name')
                    ->required(),

                TextInput::make('short_name')
                    ->required(),

                TextInput::make('abbr')
                    ->required(),

                Checkbox::make('active'),

                TextInput::make('description')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('school.name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('short_name'),

                TextColumn::make('abbr'),

                TextColumn::make('active'),

                TextColumn::make('description'),
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
            'index' => Pages\ListEnsembles::route('/'),
            'create' => Pages\CreateEnsemble::route('/create'),
            'edit' => Pages\EditEnsemble::route('/{record}/edit'),
        ];
    }

    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()->with(['school']);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'school.name'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        $details = [];

        if ($record->school) {
            $details['School'] = $record->school->name;
        }

        return $details;
    }
}
