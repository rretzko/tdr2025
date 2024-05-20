<?php

namespace App\Filament\Resources\Schools;

use App\Filament\Resources\Schools\SchoolTeacherResource\Pages;
use App\Models\Schools\SchoolTeacher;
use Faker\Core\DateTime;
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

class SchoolTeacherResource extends Resource
{
    protected static ?string $model = SchoolTeacher::class;

    protected static ?string $slug = 'schools/school-teachers';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Placeholder::make('created_at')
                    ->label('Created Date')
                    ->content(fn(?SchoolTeacher $record): string => $record?->created_at?->diffForHumans() ?? '-'),

                Placeholder::make('updated_at')
                    ->label('Last Modified Date')
                    ->content(fn(?SchoolTeacher $record): string => $record?->updated_at?->diffForHumans() ?? '-'),

                Select::make('school_id')
                    ->relationship('school', 'name')
                    ->searchable()
                    ->required(),

                Select::make('teacher_id')
                    ->relationship('teacher', 'id'),

                TextInput::make('email')
                    ->nullable()
                    ->email(),

                TextInput::make('email_verified_at')
                    ->nullable()
                    ->string(),

                Checkbox::make('active'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('school.name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('teacher_id'),

                TextColumn::make('active'),
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
            'index' => Pages\ListSchoolTeachers::route('/'),
            'create' => Pages\CreateSchoolTeacher::route('/create'),
            'edit' => Pages\EditSchoolTeacher::route('/{record}/edit'),
        ];
    }

    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()->with(['school']);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['school.name'];
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
