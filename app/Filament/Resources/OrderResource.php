<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\UserOrder;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderResource extends Resource
{
    protected static ?string $model = UserOrder::class;

    protected static ?string $navigationGroup = 'Admin';

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    protected static ?int $navigationSort = 2;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('user.name')
                    ->url(fn(UserOrder $record) =>
                        UserResource::getUrl('edit', ['record' => $record->user])
                    )
                    ->searchable(),
                TextColumn::make('items_count')->searchable(),
                TextColumn::make('orderTotal')->money('BRL'),
                TextColumn::make('created_at')->date('d/m/Y H:i:s'),
            ])
            ->filters([
                Filter::make('date_filter')->form(
                   [
                        Forms\Components\DatePicker::make('date_start'),
                        Forms\Components\DatePicker::make('date_end'),
                   ]
                )
                   ->query(function ($query, array $data) {
                        return $query
                        ->when(
                            $data['date_start'],
                            fn($query) => $query->whereDate('created_at', '>=', $data['date_start'])
                        )
                        ->when(
                            $data['date_end'],
                            fn($query) => $query->whereDate('created_at', '<=', $data['date_end'])
                        );
                   })
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
