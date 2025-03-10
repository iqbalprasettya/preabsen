<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InformationResource\Pages;
use App\Filament\Resources\InformationResource\RelationManagers;
use App\Models\Information;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DateTimePicker;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\ToggleButtons;

class InformationResource extends Resource
{
    protected static ?string $model = Information::class;

    protected static ?string $navigationIcon = 'heroicon-o-information-circle';

    protected static ?string $navigationLabel = 'Informasi';

    protected static ?string $modelLabel = 'Informasi';
    
    protected static ?string $navigationGroup = 'Pengaturan';


    protected static ?string $pluralModelLabel = 'Informasi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi')
                    ->description('Masukkan detail informasi yang akan dipublikasikan')
                    ->icon('heroicon-o-document-text')
                    ->schema([
                        Grid::make(1)
                            ->schema([
                                TextInput::make('title')
                                    ->label('Judul')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Masukkan judul informasi')
                                    ->columnSpanFull(),

                                Textarea::make('description')
                                    ->label('Deskripsi')
                                    ->required()
                                    ->rows(4)
                                    ->placeholder('Masukkan deskripsi informasi')
                                    ->columnSpanFull(),
                            ]),
                    ])
                    ->columnSpan(['lg' => 2]),

                Section::make('Pengaturan')
                    ->description('Atur kategori dan status informasi')
                    ->icon('heroicon-o-cog')
                    ->schema([
                        ToggleButtons::make('category')
                            ->label('Kategori')
                            ->required()
                            ->options([
                                'policy' => 'Kebijakan',
                                'event' => 'Acara',
                                'announcement' => 'Pengumuman',
                                'news' => 'Berita',
                            ])
                            ->icons([
                                'policy' => 'heroicon-o-scale',
                                'event' => 'heroicon-o-calendar',
                                'announcement' => 'heroicon-o-megaphone',
                                'news' => 'heroicon-o-newspaper',
                            ])
                            ->colors([
                                'policy' => 'warning',
                                'event' => 'success',
                                'announcement' => 'info',
                                'news' => 'danger',
                            ]),

                        ToggleButtons::make('status')
                            ->label('Status')
                            ->required()
                            ->options([
                                'draft' => 'Draft',
                                'published' => 'Dipublikasi',
                            ])
                            ->icons([
                                'draft' => 'heroicon-o-pencil',
                                'published' => 'heroicon-o-check-circle',
                            ])
                            ->colors([
                                'published' => 'success',
                                'draft' => 'gray',
                            ])
                            ->default('published'),

                        Forms\Components\Hidden::make('created_by')
                            ->default(fn() => auth()->id()),
                    ])
                    ->columnSpan(['lg' => 1]),
            ])
            ->columns(3);
    }

    public static function mutateFormDataBeforeCreate(array $data): array
    {
        return $data;
    }

    public static function mutateFormDataBeforeUpdate(array $data): array
    {
        return $data;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->sortable()
                    ->limit(50)
                    ->tooltip(function (Tables\Columns\TextColumn $column): string {
                        $state = $column->getState();
                        return strlen($state) > 50 ? $state : '';
                    })
                    ->description(fn(Information $record): string => $record->description)
                    ->wrap(),
                Tables\Columns\TextColumn::make('category')
                    ->label('Kategori')
                    ->badge()
                    ->colors([
                        'warning' => 'policy',
                        'success' => 'event',
                        'info' => 'announcement',
                        'danger' => 'news',
                    ])
                    ->icons([
                        'policy' => 'heroicon-o-scale',
                        'event' => 'heroicon-o-calendar',
                        'announcement' => 'heroicon-o-megaphone',
                        'news' => 'heroicon-o-newspaper',
                    ])
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'policy' => 'Kebijakan',
                        'event' => 'Acara',
                        'announcement' => 'Pengumuman',
                        'news' => 'Berita',
                        default => $state,
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->colors([
                        'gray' => 'draft',
                        'success' => 'published',
                    ])
                    ->icons([
                        'draft' => 'heroicon-o-pencil',
                        'published' => 'heroicon-o-check-circle',
                    ])
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'draft' => 'Draft',
                        'published' => 'Dipublikasi',
                        default => $state,
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('creator.name')
                    ->label('Dibuat Oleh')
                    ->icon('heroicon-m-user')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Dibuat')
                    ->icon('heroicon-m-calendar')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Tanggal Diubah')
                    ->icon('heroicon-m-clock')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->toggleable()
                    ->toggledHiddenByDefault(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->label('Kategori')
                    ->options([
                        'policy' => 'Kebijakan',
                        'event' => 'Acara',
                        'announcement' => 'Pengumuman',
                        'news' => 'Berita',
                    ])
                    ->multiple()
                    ->searchable(),
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'draft' => 'Draft',
                        'published' => 'Dipublikasi',
                    ])
                    ->multiple()
                    ->searchable(),
                Tables\Filters\TrashedFilter::make()
                    ->label('Tampilkan Data Terhapus'),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make()
                        ->label('Lihat')
                        ->icon('heroicon-m-eye'),
                    Tables\Actions\EditAction::make()
                        ->label('Edit')
                        ->icon('heroicon-m-pencil'),
                    Tables\Actions\DeleteAction::make()
                        ->label('Hapus')
                        ->icon('heroicon-m-trash'),
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Hapus Terpilih')
                        ->icon('heroicon-m-trash'),
                    Tables\Actions\RestoreBulkAction::make()
                        ->label('Pulihkan Terpilih')
                        ->icon('heroicon-m-arrow-uturn-left'),
                ]),
            ])
            ->emptyStateHeading('Belum ada informasi')
            ->emptyStateDescription('Silakan buat informasi baru dengan mengklik tombol di bawah ini.')
            ->emptyStateIcon('heroicon-o-document-text')
            ->striped()
            ->defaultPaginationPageOption(25);
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
            'index' => Pages\ListInformation::route('/'),
            'create' => Pages\CreateInformation::route('/create'),
            'edit' => Pages\EditInformation::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
