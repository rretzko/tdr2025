<?php

namespace App\Filament\Resources\Ensembles;

use App\Filament\Resources\Ensembles\AssetEnsembleResource\Pages;
use App\Models\Ensembles\AssetEnsemble;
use Filament\Forms\Components\Select;
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

class AssetEnsembleResource extends Resource
{
    protected static ?string $model = AssetEnsemble::class;

    protected static ?string $slug = 'ensembles/asset-ensembles';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('ensemble_id')
                    ->relationship('ensemble', 'name')
                    ->searchable()
                    ->required(),

                Select::make('asset_id')
                    ->relationship('asset', 'name')
                    ->searchable()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('ensemble.name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('asset.name')
                    ->searchable()
                    ->sortable(),
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
            'index' => Pages\ListAssetEnsembles::route('/'),
            'create' => Pages\CreateAssetEnsemble::route('/create'),
            'edit' => Pages\EditAssetEnsemble::route('/{record}/edit'),
        ];
    }

    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()->with(['ensemble', 'asset']);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['ensemble.name', 'asset.name'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        $details = [];

        if ($record->ensemble) {
            $details['Ensemble'] = $record->ensemble->name;
        }

        if ($record->asset) {
            $details['Asset'] = $record->asset->name;
        }

        return $details;
    }
}
