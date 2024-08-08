<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ePaymentCredentialsResource\Pages;
use App\Models\epaymentCredentials;
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

class epaymentCredentialsResource extends Resource
{
    protected static ?string $model = epaymentCredentials::class;

    protected static ?string $slug = 'e-payment-credentials';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Placeholder::make('created_at')
                    ->label('Created Date')
                    ->content(fn(?epaymentCredentials $record
                    ): string => $record?->created_at?->diffForHumans() ?? '-'),

                Placeholder::make('updated_at')
                    ->label('Last Modified Date')
                    ->content(fn(?epaymentCredentials $record
                    ): string => $record?->updated_at?->diffForHumans() ?? '-'),

                Select::make('event_id')
                    ->relationship('event', 'name')
                    ->searchable()
                    ->required(),

                TextInput::make('version_id')
                    ->required()
                    ->integer(),

                TextInput::make('ePaymentId')
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

                TextColumn::make('version_id'),

                TextColumn::make('ePaymentId'),
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
            'index' => Pages\ListePaymentCredentials::route('/'),
            'create' => Pages\CreateePaymentCredentials::route('/create'),
            'edit' => Pages\EditePaymentCredentials::route('/{record}/edit'),
        ];
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
