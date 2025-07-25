<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AttendanceResource\Pages;
use App\Filament\Resources\AttendanceResource\RelationManagers;
use App\Models\Attendance;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AttendanceResource extends Resource
{
    protected static ?string $model = Attendance::class;
    protected static ?string $navigationIcon = 'heroicon-o-clock';
    protected static ?string $navigationLabel = 'Absensi';
    protected static ?int $navigationSort = 1;
    protected static ?string $recordTitleAttribute = 'id';
    protected static ?string $slug = 'attendance';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Informasi Karyawan')
                            ->schema(static::getEmployeeFormSchema())
                            ->columns(1),

                        Forms\Components\Section::make('Waktu dan Lokasi')
                            ->schema(static::getTimeLocationFormSchema())
                            ->columns(2),

                        Forms\Components\Section::make('Lokasi Maps')
                            ->schema([
                                Forms\Components\View::make('filament.forms.components.attendance-map')
                            ])
                            ->columnSpanFull()
                            ->collapsed(false)
                            ->collapsible()
                            ->visible(function ($livewire) {
                                return $livewire instanceof Pages\ViewAttendance;
                            }),

                        Forms\Components\Section::make('Foto Kehadiran')
                            ->schema(static::getPhotoFormSchema())
                            ->columns(2),
                    ])
                    ->columnSpan(['lg' => fn(?Attendance $record) => $record === null ? 3 : 2]),

                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Status dan Catatan')
                            ->schema(static::getStatusFormSchema()),

                        Forms\Components\Section::make('Informasi Tambahan')
                            ->schema([
                                Forms\Components\Placeholder::make('created_at')
                                    ->label('Dibuat pada')
                                    ->content(fn(Attendance $record): ?string => $record->created_at?->diffForHumans()),
                                Forms\Components\Placeholder::make('updated_at')
                                    ->label('Terakhir diubah')
                                    ->content(fn(Attendance $record): ?string => $record->updated_at?->diffForHumans()),
                            ])
                            ->hidden(fn(?Attendance $record) => $record === null),
                    ])
                    ->columnSpan(['lg' => 1]),
            ])
            ->columns(3);
    }

    public static function getEmployeeFormSchema(): array
    {
        return [
            Forms\Components\Select::make('user_id')
                ->relationship('user', 'name')
                ->default(fn() => auth()->id())
                ->disabled(fn() => auth()->user()->role !== 'admin')
                ->dehydrated(true)
                ->required(),
        ];
    }

    public static function getTimeLocationFormSchema(): array
    {
        return [
            Forms\Components\DateTimePicker::make('check_in')
                ->label('Waktu Masuk')
                ->required(),
            Forms\Components\TextInput::make('check_in_address')
                ->label('Alamat Masuk'),
            Forms\Components\Grid::make(2)
                ->schema([
                    Forms\Components\TextInput::make('check_in_latitude')
                        ->label('Latitude Masuk')
                        ->numeric()
                        ->required(),
                    Forms\Components\TextInput::make('check_in_longitude')
                        ->label('Longitude Masuk')
                        ->numeric()
                        ->required(),
                ]),
            Forms\Components\DateTimePicker::make('check_out')
                ->label('Waktu Keluar'),
            Forms\Components\TextInput::make('check_out_address')
                ->label('Alamat Keluar'),
            Forms\Components\Grid::make(2)
                ->schema([
                    Forms\Components\TextInput::make('check_out_latitude')
                        ->label('Latitude Keluar')
                        ->numeric(),
                    Forms\Components\TextInput::make('check_out_longitude')
                        ->label('Longitude Keluar')
                        ->numeric(),
                ]),
        ];
    }

    public static function getPhotoFormSchema(): array
    {
        return [
            Forms\Components\FileUpload::make('check_in_photo')
                ->label('Foto Masuk')
                ->image()
                ->directory('attendance-photos')
                ->maxSize(5120),
            Forms\Components\FileUpload::make('check_out_photo')
                ->label('Foto Keluar')
                ->image()
                ->directory('attendance-photos')
                ->maxSize(5120),
        ];
    }

    public static function getStatusFormSchema(): array
    {
        return [
            Forms\Components\Select::make('status')
                ->options([
                    'present' => 'Hadir',
                    'half_day' => 'Setengah Hari',
                    'overtime' => 'Lembur',
                    'absent' => 'Tidak Hadir',
                    'late' => 'Terlambat',
                    'early' => 'Lebih Awal',
                ]),
            Forms\Components\Textarea::make('notes')
                ->label('Catatan')
                ->columnSpanFull(),
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Nama Karyawan')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('check_in')
                    ->label('Waktu Masuk')
                    ->dateTime('d M Y H:i')
                    ->sortable(),

                Tables\Columns\ImageColumn::make('check_in_photo')
                    ->label('Foto Masuk')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('check_out')
                    ->label('Waktu Keluar')
                    ->dateTime('d M Y H:i')
                    ->sortable(),

                Tables\Columns\ImageColumn::make('check_out_photo')
                    ->label('Foto Keluar')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->icon(fn(string $state): string => match ($state) {
                        'present' => 'heroicon-m-check-circle',
                        'late' => 'heroicon-m-clock',
                        'half_day' => 'heroicon-m-arrow-left',
                        'overtime' => 'heroicon-m-clock',
                        'absent' => 'heroicon-m-x-circle',
                        'early' => 'heroicon-m-arrow-up',
                    })
                    ->color(fn(string $state): string => match ($state) {
                        'present' => 'success',
                        'late' => 'warning',
                        'half_day' => 'info',
                        'overtime' => 'warning',
                        'absent' => 'danger',
                        'early' => 'info',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'present' => 'Hadir',
                        'late' => 'Terlambat',
                        'half_day' => 'Setengah Hari',
                        'overtime' => 'Lembur',
                        'absent' => 'Tidak Hadir',
                        'early' => 'Lebih Awal',
                    }),
            ])
            ->defaultSort('check_in', 'desc')
            ->modifyQueryUsing(function (Builder $query) {
                if (!auth()->user()->hasRole(['super_admin', 'admin'])) {
                    $query->where('user_id', auth()->id());
                }
            })
            ->filters([
                Tables\Filters\TernaryFilter::make('check_in_photo')
                    ->label('Foto Masuk')
                    ->placeholder('Semua')
                    ->trueLabel('Ada')
                    ->falseLabel('Tidak Ada'),
                Tables\Filters\TernaryFilter::make('check_out_photo')
                    ->label('Foto Keluar')
                    ->placeholder('Semua')
                    ->trueLabel('Ada')
                    ->falseLabel('Tidak Ada'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListAttendances::route('/'),
            'create' => Pages\CreateAttendance::route('/create'),
            'view' => Pages\ViewAttendance::route('/{record}'),
            'edit' => Pages\EditAttendance::route('/{record}/edit'),
        ];
    }
}
