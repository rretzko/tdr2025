<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EpaymentResource\Pages;
use App\Models\Epayment;
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

class EpaymentResource extends Resource
{
    protected static ?string $model = Epayment::class;

    protected static ?string $slug = 'epayments';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Placeholder::make('created_at')
                    ->label('Created Date')
                    ->content(fn(?Epayment $record): string => $record?->created_at?->diffForHumans() ?? '-'),

                Placeholder::make('updated_at')
                    ->label('Last Modified Date')
                    ->content(fn(?Epayment $record): string => $record?->updated_at?->diffForHumans() ?? '-'),

                Select::make('version_id')
                    ->relationship('version', 'name')
                    ->searchable()
                    ->required(),

                Select::make('school_id')
                    ->relationship('school', 'name')
                    ->searchable()
                    ->required(),

                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->required(),

                TextInput::make('fee_type')
                    ->required(),

                TextInput::make('candidate_id')
                    ->required()
                    ->integer(),

                TextInput::make('transaction_id')
                    ->required(),

                TextInput::make('amount')
                    ->required()
                    ->integer(),

                TextInput::make('comments')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('version.name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('school.name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('user.name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('fee_type'),

                TextColumn::make('candidate_id'),

                TextColumn::make('transaction_id'),

                TextColumn::make('amount'),

                TextColumn::make('comments'),
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
            'index' => Pages\ListEpayments::route('/'),
            'create' => Pages\CreateEpayment::route('/create'),
            'edit' => Pages\EditEpayment::route('/{record}/edit'),
        ];
    }

    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()->with(['version', 'school', 'user']);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['version.name', 'school.name', 'user.name'];
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

        if ($record->user) {
            $details['User'] = $record->user->name;
        }

        return $details;
    }
}
