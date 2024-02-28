<?php

namespace App\Filament\Resources;

use App\Models\Product;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Select;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Actions\DeleteAction;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use App\Filament\Resources\ProductResource\Pages;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('lightspeed_product_id')->required()->unique(ignorable: fn ($record) => $record),
                TextInput::make('name')->required(),
                TextInput::make('description'),
                TextInput::make('price')->numeric(),
                FileUpload::make('image')
                    ->image()
                    ->disk('public'),
                Select::make('category_id')
                    ->relationship(name: 'category', titleAttribute: 'name')->required()

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('lightspeed_product_id'),
                TextColumn::make('name'),
                TextColumn::make('description'),
                TextColumn::make('price'),
                // Tables\Columns\TextColumn::make('image'),
                ImageColumn::make('image'),
                // Tables\Columns\TextColumn::make('category_id'),
                TextColumn::make('category.name'),
            ])
            ->filters([
                Filter::make('name')->query(fn (Builder $query): Builder => $query->whereNotNull('price')),
                Filter::make('price')->query(fn (Builder $query): Builder => $query->whereNotNull('price')),
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
