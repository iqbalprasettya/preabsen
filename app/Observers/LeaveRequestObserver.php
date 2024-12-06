<?php

namespace App\Observers;

use App\Models\LeaveRequest;
use Filament\Notifications\Notification;
use App\Models\User;

class LeaveRequestObserver
{
    /**
     * Handle the LeaveRequest "created" event.
     */
    public function created(LeaveRequest $leaveRequest): void
    {
        $recipient = User::where('role', 'admin')->first();
        Notification::make()
            ->title('Pengajuan Cuti Baru')
            ->icon('heroicon-o-clipboard-document-check')
            ->body('Pengajuan cuti baru dari: ' . $leaveRequest->user->name)
            ->actions([
                \Filament\Notifications\Actions\Action::make('view')
                    ->label('Lihat Pengajuan')
                    ->url('/admin/approvals/')
            ])
            ->sendToDatabase($recipient);
    }

    /**
     * Handle the LeaveRequest "updated" event.
     */
    public function updated(LeaveRequest $leaveRequest): void
    {
        $recipient = $leaveRequest->user;

        // Cek apakah status berubah menjadi approved/rejected
        if ($leaveRequest->isDirty('status')) {
            if ($leaveRequest->status === 'approved') {
                Notification::make()
                    ->title('Pengajuan Cuti Disetujui')
                    ->icon('heroicon-o-check-circle')
                    ->body('Selamat! Pengajuan cuti Anda telah disetujui.')
                    ->actions([
                        \Filament\Notifications\Actions\Action::make('view')
                            ->label('Lihat Detail')
                            ->url('/admin/leave-requests')
                    ])
                    ->sendToDatabase($recipient);
            } elseif ($leaveRequest->status === 'rejected') {
                Notification::make()
                    ->title('Pengajuan Cuti Ditolak')
                    ->icon('heroicon-o-x-circle')
                    ->body('Maaf, pengajuan cuti Anda ditolak.')
                    ->actions([
                        \Filament\Notifications\Actions\Action::make('view')
                            ->label('Lihat Detail')
                            ->url('/admin/leave-requests')
                    ])
                    ->sendToDatabase($recipient);
            }
        } else {
            // Jika perubahan bukan pada status, kirim notifikasi perubahan data
            Notification::make()
                ->title('Perubahan Data Pengajuan')
                ->icon('heroicon-o-pencil')
                ->body('Data pengajuan izin Anda telah diperbarui. Silakan cek detail perubahan.')
                ->actions([
                    \Filament\Notifications\Actions\Action::make('view')
                        ->label('Lihat Detail')
                        ->url('/admin/leave-requests')
                ])
                ->sendToDatabase($recipient);
        }
    }

    /**
     * Handle the LeaveRequest "deleted" event.
     */
    public function deleted(LeaveRequest $leaveRequest): void
    {
        $recipient = $leaveRequest->user;
        Notification::make()
            ->title('Pengajuan Cuti Dihapus')
            ->icon('heroicon-o-trash')
            ->body('Pengajuan cuti Anda telah dihapus')
            ->actions([
                \Filament\Notifications\Actions\Action::make('view')
                    ->label('Lihat Riwayat')
                    ->url('/admin/leave-requests')
            ])
            ->sendToDatabase($recipient);
    }

    /**
     * Handle the LeaveRequest "restored" event.
     */
    public function restored(LeaveRequest $leaveRequest): void
    {
        $recipient = $leaveRequest->user;
        Notification::make()
            ->title('Pengajuan Cuti Dipulihkan')
            ->icon('heroicon-o-arrow-path')
            ->body('Pengajuan cuti Anda telah dipulihkan')
            ->actions([
                \Filament\Notifications\Actions\Action::make('view')
                    ->label('Lihat Detail')
                    ->url('/admin/leave-requests')
            ])
            ->sendToDatabase($recipient);
    }

    /**
     * Handle the LeaveRequest "force deleted" event.
     */
    public function forceDeleted(LeaveRequest $leaveRequest): void
    {
        $recipient = $leaveRequest->user;
        Notification::make()
            ->title('Pengajuan Cuti Dihapus Permanen')
            ->icon('heroicon-o-x-circle')
            ->body('Pengajuan cuti Anda telah dihapus secara permanen')
            ->actions([
                \Filament\Notifications\Actions\Action::make('view')
                    ->label('Lihat Riwayat')
                    ->url('/admin/leave-requests')
            ])
            ->sendToDatabase($recipient);
    }
}
