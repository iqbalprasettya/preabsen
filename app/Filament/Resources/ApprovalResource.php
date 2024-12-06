<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ApprovalResource\Pages;
use App\Models\LeaveRequest;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Section;
use Filament\Support\Enums\FontWeight;
use Illuminate\Support\Facades\Storage;

class ApprovalResource extends Resource
{
    protected static ?string $model = LeaveRequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static ?string $modelLabel = 'Approval';

    protected static ?string $pluralModelLabel = 'Approvals';

    protected static ?string $navigationLabel = 'Persetujuan Cuti';

    protected static ?string $navigationGroup = 'Pengaturan';

    protected static ?int $navigationSort = 3;

    protected static ?string $breadcrumb = 'Persetujuan Cuti';

    public static function canCreate(): bool
    {
        return false; // Menonaktifkan tombol create/new
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Nama Karyawan')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('type')
                    ->label('Tipe Cuti')
                    ->badge()
                    ->icon(fn(string $state): string => match ($state) {
                        'annual' => 'heroicon-m-calendar',
                        'sick' => 'heroicon-m-heart',
                        'important' => 'heroicon-m-exclamation-triangle',
                        'other' => 'heroicon-m-document',
                    })
                    ->color(fn(string $state): string => match ($state) {
                        'annual' => 'info',
                        'sick' => 'warning',
                        'important' => 'danger',
                        'other' => 'gray',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'annual' => 'Cuti Tahunan',
                        'sick' => 'Sakit',
                        'important' => 'Kepentingan',
                        'other' => 'Lainnya',
                    }),

                Tables\Columns\TextColumn::make('start_date')
                    ->label('Tanggal Mulai')
                    ->date('d M Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('end_date')
                    ->label('Tanggal Selesai')
                    ->date('d M Y')
                    ->sortable(),



                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->icon(fn(string $state): string => match ($state) {
                        'pending' => 'heroicon-m-clock',
                        'approved' => 'heroicon-m-check-circle',
                        'rejected' => 'heroicon-m-x-circle',
                    })
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'gray',
                        'approved' => 'success',
                        'rejected' => 'danger',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'pending' => 'Menunggu',
                        'approved' => 'Disetujui',
                        'rejected' => 'Ditolak',
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Menunggu',
                        'approved' => 'Disetujui',
                        'rejected' => 'Ditolak'
                    ])
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('approve')
                    ->label('Setujui')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->action(function (LeaveRequest $record) {
                        $record->update([
                            'status' => 'approved',
                            'approved_by' => auth()->id(),
                            'approved_at' => now(),
                        ]);
                    })
                    ->visible(fn(LeaveRequest $record) => $record->status === 'pending'),

                Tables\Actions\Action::make('reject')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-mark')
                    ->color('danger')
                    ->action(function (LeaveRequest $record) {
                        $record->update([
                            'status' => 'rejected',
                            'approved_by' => auth()->id(),
                            'approved_at' => now(),
                        ]);
                    })
                    ->visible(fn(LeaveRequest $record) => $record->status === 'pending'),
            ])
            ->bulkActions([])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListApprovals::route('/'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('status', 'pending');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Informasi Karyawan')
                    ->schema([
                        TextEntry::make('user.name')
                            ->label('Nama Karyawan'),
                        TextEntry::make('user.employee_id')
                            ->label('NIP'),
                        TextEntry::make('user.departement.name')
                            ->label('Departemen'),
                    ])
                    ->columns(3),

                Section::make('Detail Pengajuan Cuti')
                    ->schema([
                        TextEntry::make('type')
                            ->label('Tipe Cuti')
                            ->badge()
                            ->formatStateUsing(fn(string $state): string => match ($state) {
                                'annual' => 'Cuti Tahunan',
                                'sick' => 'Sakit',
                                'important' => 'Kepentingan',
                                'other' => 'Lainnya',
                            })
                            ->color(fn(string $state): string => match ($state) {
                                'annual' => 'info',
                                'sick' => 'warning',
                                'important' => 'danger',
                                'other' => 'gray',
                            }),
                        TextEntry::make('start_date')
                            ->label('Tanggal Mulai')
                            ->date('d M Y'),
                        TextEntry::make('end_date')
                            ->label('Tanggal Selesai')
                            ->date('d M Y'),
                        TextEntry::make('description')
                            ->label('Keterangan')
                            ->columnSpanFull(),
                        TextEntry::make('attachment')
                            ->label('Lampiran')
                            ->formatStateUsing(function ($state) {
                                if (!$state) return '-';
                                return 'Lihat Lampiran';
                            })
                            ->url(fn($record) => $record->attachment ? Storage::url($record->attachment) : null)
                            ->openUrlInNewTab()
                            ->icon('heroicon-m-paper-clip')
                            ->color('primary')
                            ->weight(FontWeight::Bold),
                        TextEntry::make('status')
                            ->label('Status')
                            ->badge()
                            ->formatStateUsing(fn(string $state): string => match ($state) {
                                'pending' => 'Menunggu',
                                'approved' => 'Disetujui',
                                'rejected' => 'Ditolak',
                            })
                            ->color(fn(string $state): string => match ($state) {
                                'pending' => 'gray',
                                'approved' => 'success',
                                'rejected' => 'danger',
                            }),
                        TextEntry::make('created_at')
                            ->label('Diajukan Pada')
                            ->dateTime('d M Y H:i'),
                    ])
                    ->columns(3),
            ]);
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'pending')->count();
    }
}
