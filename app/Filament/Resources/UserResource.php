<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\ImageColumn;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    
    protected static ?string $slug = 'settings/users';

    protected static ?string $navigationGroup = 'Pengaturan';

    protected static ?string $navigationLabel = 'Pengguna';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Informasi Pengguna')
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('Nama')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('email')
                                    ->email()
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('password')
                                    ->label('Password')
                                    ->password()
                                    ->revealable()
                                    ->dehydrateStateUsing(fn ($state) => filled($state) ? bcrypt($state) : null)
                                    ->required(fn (string $context): bool => $context === 'create')
                                    ->dehydrated(fn ($state) => filled($state))
                                    ->maxLength(255),
                                Forms\Components\Select::make('departement_id')
                                    ->label('Departemen')
                                    ->relationship('departement', 'name')
                                    ->searchable()
                                    ->preload(),
                                Forms\Components\Select::make('office_location_id')
                                    ->label('Lokasi Kantor')
                                    ->relationship('officeLocation', 'name')
                                    ->searchable()
                                    ->preload(),
                                Forms\Components\Select::make('work_schedule_id')
                                    ->label('Jadwal Kerja')
                                    ->relationship('workSchedule', 'name')
                                    ->searchable()
                                    ->preload(),
                                Forms\Components\Select::make('roles')
                                    ->label('Hak Akses')
                                    ->multiple()
                                    ->relationship('roles', 'name')
                                    ->preload()
                                    ->searchable()
                                    ->required(),
                            ])
                            ->columns(2),

                        Forms\Components\Section::make('Detail Karyawan')
                            ->schema([
                                Forms\Components\TextInput::make('phone_number')
                                    ->label('Nomor Telepon')
                                    ->tel()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('position')
                                    ->label('Jabatan')
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('employee_id')
                                    ->label('NIP')
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(255),
                                Forms\Components\Textarea::make('address')
                                    ->label('Alamat')
                                    ->columnSpanFull(),
                                Forms\Components\FileUpload::make('photo')
                                    ->image()
                                    ->imageEditor()
                                    ->directory('user-photos')
                                    ->visibility('public')
                                    ->maxSize(2048)
                                    ->columnSpanFull(),
                            ])
                            ->columns(2),
                    ])
                    ->columnSpan(['lg' => 2]),

                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Placeholder::make('created_at')
                            ->label('Dibuat')
                            ->content(fn ($record): ?string => $record?->created_at?->diffForHumans()),

                        Forms\Components\Placeholder::make('updated_at')
                            ->label('Terakhir Diubah')
                            ->content(fn ($record): ?string => $record?->updated_at?->diffForHumans()),
                    ])
                    ->columnSpan(['lg' => 1])
                    ->hidden(fn ($record) => $record === null),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('photo')
                    ->circular()
                    ->defaultImageUrl(url('/storage/user-photos/default.png'))
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('departement.name')
                    ->label('Departemen')
                    ->sortable(),
                Tables\Columns\TextColumn::make('officeLocation.name')
                    ->label('Lokasi Kantor')
                    ->sortable(),
                Tables\Columns\TextColumn::make('workSchedule.name')
                    ->label('Jadwal Kerja')
                    ->sortable(),
                Tables\Columns\TextColumn::make('phone_number')
                    ->label('Nomor Telepon')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('position')
                    ->label('Jabatan')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('employee_id')
                    ->label('NIP')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->label('Dihapus')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('roles.name')
                    ->label('Role')
                    ->badge()
                    ->separator(',')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('departement')
                    ->label('Departemen')
                    ->relationship('departement', 'name'),
                Tables\Filters\SelectFilter::make('office_location')
                    ->label('Lokasi Kantor')
                    ->relationship('officeLocation', 'name'),
                Tables\Filters\SelectFilter::make('work_schedule')
                    ->label('Jadwal Kerja')
                    ->relationship('workSchedule', 'name'),
                Tables\Filters\TrashedFilter::make()
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
