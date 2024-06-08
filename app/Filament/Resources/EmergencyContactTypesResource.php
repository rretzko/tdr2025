<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmergencyContactTypesResource\Pages;
use App\Models\Students\EmergencyContactType;
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

class EmergencyContactTypesResource extends Resource
{
    protected static ?string $model = EmergencyContactType::class;

    protected static ?string $slug = 'emergency-contact-types';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Placeholder::make('created_at')
                    ->label('Created Date')
                    ->content(fn(?EmergencyContactType $record
                    ): string => $record?->created_at?->diffForHumans() ?? '-'),

                Placeholder::make('updated_at')
                    ->label('Last Modified Date')
                    ->content(fn(?EmergencyContactType $record
                    ): string => $record?->updated_at?->diffForHumans() ?? '-'),

                TextInput::make('relationship')
                    ->required(),

                TextInput::make('pronoun_id')
                    ->required()
                    ->integer(),

                TextInput::make('order_by')
                    ->required()
                    ->integer(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('relationship'),

                TextColumn::make('pronoun_id'),

                TextColumn::make('order_by'),
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
            'index' => Pages\ListEmergencyContactTypes::route('/'),
            'create' => Pages\CreateEmergencyContactTypes::route('/create'),
            'edit' => Pages\EditEmergencyContactTypes::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [];
    }
}
