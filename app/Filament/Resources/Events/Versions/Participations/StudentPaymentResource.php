<?php

namespace App\Filament\Resources\Events\Versions\Participations;

use App\Filament\Resources\Events\Versions\Participations\StudentPaymentResource\Pages;
use App\Models\Events\Versions\Participations\StudentPayment;
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

class StudentPaymentResource extends Resource
{
    protected static ?string $model = StudentPayment::class;

    protected static ?string $slug = 'events/versions/participations/student-payments';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Placeholder::make('created_at')
                    ->label('Created Date')
                    ->content(fn(?StudentPayment $record): string => $record?->created_at?->diffForHumans() ?? '-'),

                Placeholder::make('updated_at')
                    ->label('Last Modified Date')
                    ->content(fn(?StudentPayment $record): string => $record?->updated_at?->diffForHumans() ?? '-'),

                TextInput::make('candidate_id')
                    ->required()
                    ->integer(),

                TextInput::make('student_id')
                    ->required()
                    ->integer(),

                Select::make('version_id')
                    ->relationship('version', 'name')
                    ->searchable()
                    ->required(),

                Select::make('school_id')
                    ->relationship('school', 'name')
                    ->searchable()
                    ->required(),

                TextInput::make('amount')
                    ->required()
                    ->integer(),

                TextInput::make('transaction_id')
                    ->required(),

                TextInput::make('comments')
                    ->required(),

                TextInput::make('payment_type')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('candidate_id'),

                TextColumn::make('student_id'),

                TextColumn::make('version.name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('school.name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('amount'),

                TextColumn::make('transaction_id'),

                TextColumn::make('comments'),

                TextColumn::make('payment_type'),
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
            'index' => Pages\ListStudentPayments::route('/'),
            'create' => Pages\CreateStudentPayment::route('/create'),
            'edit' => Pages\EditStudentPayment::route('/{record}/edit'),
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
