<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LeaveRequestResource\Pages;
use App\Filament\Resources\LeaveRequestResource\RelationManagers;
use App\Models\LeaveRequest;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Database\Eloquent\Model;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Section;
use Filament\Support\Enums\FontWeight;
use Illuminate\Support\Facades\Storage;

class LeaveRequestResource extends Resource
{
    protected static ?string $model = LeaveRequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $recordTitleAttribute = 'type';

    protected static ?string $navigationLabel = 'Permohonan Cuti';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make()
                            ->schema(static::getFormSchema())
                            ->columns(2),
                    ])
                    ->columnSpan(['lg' => fn(?LeaveRequest $record) => $record === null ? 3 : 2]),

                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Placeholder::make('created_at')
                            ->label('Dibuat pada')
                            ->content(fn(LeaveRequest $record): ?string => $record->created_at?->diffForHumans()),

                        Forms\Components\Placeholder::make('updated_at')
                            ->label('Terakhir diubah')
                            ->content(fn(LeaveRequest $record): ?string => $record->updated_at?->diffForHumans()),
                    ])
                    ->columnSpan(['lg' => 1])
                    ->hidden(fn(?LeaveRequest $record) => $record === null),
            ])
            ->columns(3);
    }

    public static function getFormSchema(): array
    {
        return [
            Forms\Components\Hidden::make('user_id')
                ->default(fn() => auth()->id()),

            Forms\Components\Select::make('type')
                ->options([
                    'annual' => 'Cuti Tahunan',
                    'sick' => 'Sakit',
                    'important' => 'Kepentingan',
                    'other' => 'Lainnya'
                ])
                ->required(),

            Forms\Components\DatePicker::make('start_date')
                ->required(),

            Forms\Components\DatePicker::make('end_date')
                ->required(),

            Forms\Components\Textarea::make('description')
                ->required()
                ->columnSpanFull(),

            Forms\Components\FileUpload::make('attachment')
                ->helperText('Maksimal ukuran file 5MB. Format yang diizinkan: Gambar, PDF, dan Dokumen Word.')
                ->directory('leave-attachments')
                ->maxSize(5120)
                ->acceptedFileTypes([
                    'image/*',
                    'application/pdf',
                    'application/msword',
                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
                ])
                ->downloadable()
                ->openable(),
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

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->modifyQueryUsing(function (Builder $query) {
                if (!auth()->user()->hasRole(['super_admin', 'admin'])) {
                    $query->where('user_id', auth()->id());
                }
            })
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Menunggu',
                        'approved' => 'Disetujui',
                        'rejected' => 'Ditolak'
                    ])
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make()
                        ->modalContent(
                            fn(LeaveRequest $record): Infolist =>
                            static::infolist(Infolist::make())
                                ->record($record)
                        )
                        ->modalSubmitAction(false)
                        ->modalCancelAction(false),

                    Tables\Actions\EditAction::make()
                        ->modalContent(
                            fn(LeaveRequest $record, Form $form): Form =>
                            $form->schema(static::getFormSchema())
                        )
                        ->visible(fn(LeaveRequest $record): bool => auth()->user()->can('update', $record)),

                    Tables\Actions\Action::make('approve')
                        ->label('Setujui')
                        ->icon('heroicon-o-check')
                        ->color('success')
                        ->requiresConfirmation()
                        ->modalHeading('Setujui Permohonan Cuti')
                        ->modalDescription('Apakah Anda yakin ingin menyetujui permohonan cuti ini?')
                        ->action(function (LeaveRequest $record) {
                            $record->update([
                                'status' => 'approved',
                                'approved_by' => auth()->id(),
                                'approved_at' => now(),
                            ]);
                        })
                        ->visible(
                            fn(LeaveRequest $record): bool =>
                            auth()->user()->can('approve', $record) &&
                                $record->status === 'pending'
                        ),

                    Tables\Actions\Action::make('reject')
                        ->label('Tolak')
                        ->icon('heroicon-o-x-mark')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->modalHeading('Tolak Permohonan Cuti')
                        ->modalDescription('Apakah Anda yakin ingin menolak permohonan cuti ini?')
                        ->action(function (LeaveRequest $record) {
                            $record->update([
                                'status' => 'rejected',
                                'approved_by' => auth()->id(),
                                'approved_at' => now(),
                            ]);
                        })
                        ->visible(
                            fn(LeaveRequest $record): bool =>
                            auth()->user()->can('reject', $record) &&
                                $record->status === 'pending'
                        ),

                    Tables\Actions\DeleteAction::make()
                        ->visible(fn(LeaveRequest $record): bool => auth()->user()->can('delete', $record)),
                ])
            ])
            ->bulkActions([]);
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
            'index' => Pages\ListLeaveRequests::route('/'),
            'create' => Pages\CreateLeaveRequest::route('/create'),
            'edit' => Pages\EditLeaveRequest::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        $query = static::getModel()::where('status', 'pending');

        if (!auth()->user()->hasRole(['super_admin', 'admin'])) {
            $query->where('user_id', auth()->id());
        }

        return $query->count();
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
}
