<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmergencyContactResource\Pages;
use App\Models\EmergencyContact;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class EmergencyContactResource extends Resource
{
    protected static ?string $model = EmergencyContact::class;

    protected static ?string $slug = 'emergency-contacts';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Placeholder::make('created_at')
                    ->label('Created Date')
                    ->content(fn(?EmergencyContact $record): string => $record?->created_at?->diffForHumans() ?? '-'),

                Placeholder::make('updated_at')
                    ->label('Last Modified Date')
                    ->content(fn(?EmergencyContact $record): string => $record?->updated_at?->diffForHumans() ?? '-'),

                TextInput::make('student_id')
                    ->required()
                    ->integer(),

                TextInput::make('emergency_contact_type_id')
                    ->required()
                    ->integer(),

                TextInput::make('name')
                    ->required(),

                TextInput::make('email')
                    ->required(),

                TextInput::make('phoneHome')
                    ->required(),

                TextInput::make('phoneMobile')
                    ->required(),

                TextInput::make('phoneWork')
                    ->required(),

                TextInput::make('bestPhone')
                    ->required()
                    ->integer(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('student_id'),

                TextColumn::make('emergency_contact_type_id'),

                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('phoneHome'),

                TextColumn::make('phoneMobile'),

                TextColumn::make('phoneWork'),

                TextColumn::make('bestPhone'),
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
            'index' => Pages\ListEmergencyContacts::route('/'),
            'create' => Pages\CreateEmergencyContact::route('/create'),
            'edit' => Pages\EditEmergencyContact::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'email'];
    }
}
