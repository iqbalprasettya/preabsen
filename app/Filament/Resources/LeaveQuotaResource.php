<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LeaveQuotaResource\Pages;
use App\Models\LeaveQuota;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class LeaveQuotaResource extends Resource
{
    protected static ?string $model = LeaveQuota::class;

    protected static ?string $navigationIcon = 'heroicon-o-calculator';

    protected static ?string $navigationLabel = 'Kuota Cuti';
    
    protected static ?string $navigationGroup = 'Pengaturan';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->label('Karyawan'),

                Forms\Components\TextInput::make('year')
                    ->label('Tahun')
                    ->default(now()->year)
                    ->required()
                    ->numeric()
                    ->minValue(2024)
                    ->maxValue(2100),

                Forms\Components\TextInput::make('annual_quota')
                    ->label('Kuota Tahunan')
                    ->required()
                    ->numeric()
                    ->default(12)
                    ->minValue(0)
                    ->maxValue(365),

                Forms\Components\TextInput::make('used_quota')
                    ->label('Kuota Terpakai')
                    ->numeric()
                    ->default(0)
                    ->disabled(),

                Forms\Components\TextInput::make('remaining_quota')
                    ->label('Sisa Kuota')
                    ->numeric()
                    ->default(12)
                    ->disabled(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Karyawan')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('year')
                    ->label('Tahun')
                    ->sortable(),

                Tables\Columns\TextColumn::make('annual_quota')
                    ->label('Kuota Tahunan')
                    ->sortable(),

                Tables\Columns\TextColumn::make('used_quota')
                    ->label('Kuota Terpakai')
                    ->sortable(),

                Tables\Columns\TextColumn::make('remaining_quota')
                    ->label('Sisa Kuota')
                    ->sortable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Terakhir Diperbarui')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('year')
                    ->options(fn() => [
                        now()->year => 'Tahun Ini',
                        now()->subYear()->year => 'Tahun Lalu',
                        now()->addYear()->year => 'Tahun Depan',
                    ])
                    ->label('Tahun'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListLeaveQuotas::route('/'),
            'create' => Pages\CreateLeaveQuota::route('/create'),
            'edit' => Pages\EditLeaveQuota::route('/{record}/edit'),
        ];
    }
}
