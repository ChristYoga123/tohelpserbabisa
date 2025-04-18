<?php

namespace App\Filament\Karyawan\Widgets;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Absensi;
use Filament\Tables\Table;
use App\Models\AbsensiBase;
use Illuminate\Support\Facades\DB;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Notifications\Notification;
use Filament\Forms\Components\FileUpload;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;

class AbsensiWidget extends BaseWidget
{
    public function table(Table $table): Table
    {
        return $table
            ->query(
                User::query()
                    ->whereId(auth()->user()->id)
                    ->orWhereHas('absensi', function($query)
                    {
                        $query->whereDate('tanggal', now());
                    })
            )
            ->columns([
                TextColumn::make('name')
                    ->label('Karyawan'),
                TextColumn::make('absensi.jam_masuk')
                    ->label('Jam Masuk')
                    ->getStateUsing(function(User $user)
                    {
                        $latestAbsensi = $user->absensi()
                            ->whereDate('tanggal', now())
                            ->latest()
                            ->first();
                        if(!$latestAbsensi)
                        {
                            return '-';
                        }

                        return Carbon::parse($latestAbsensi->jam_masuk)->format('H:i:s');
                    }),
                TextColumn::make('status')
                    ->badge()
                    ->color(function(User $user)
                    {
                        $latestAbsensi = $user->absensi()
                            ->whereDate('tanggal', now())
                            ->latest()
                            ->first();
                        if(!$latestAbsensi)
                        {
                            return 'warning';
                        }

                        return 'success';
                    })
                    ->getStateUsing(function(User $user)
                    {
                        $latestAbsensi = $user->absensi()
                            ->whereDate('tanggal', now())
                            ->latest()
                            ->first();
                        if(!$latestAbsensi)
                        {
                            return 'Belum Stand By';
                        }

                        return 'Stand By';
                    }),
            ])
            ->actions([
                Action::make('uploadBuktiAbsen')
                    ->closeModalByClickingAway(false)
                    ->label('Upload Bukti Absen')
                    ->icon('heroicon-o-arrow-up-tray')
                    ->visible(function(User $user)
                    {
                        return !$user->absensi()
                            ->whereDate('tanggal', now())
                            ->exists();
                    })
                    ->button()
                    ->form([
                        FileUpload::make('bukti_absen')
                            ->label('Bukti Absen')
                            ->image()
                            ->maxFiles(1)
                            ->optimize('webp')
                            ->required(),
                    ])
                    ->action(function(array $data, User $record)
                    {
                        // cek waktu yang ditetapkan untuk bisa absen
                        $masterAbsensi = AbsensiBase::first();

                        // jika jam absen belum mulai atau sudah lewat, maka berikan validasi tidak bisa absen
                        if(now()->isBefore($masterAbsensi->jam_masuk) || now()->isAfter($masterAbsensi->jam_keluar))
                        {
                            Notification::make()
                                ->title('Gagal')
                                ->body('Waktu absen sudah lewat atau belum dimulai')
                                ->danger()
                                ->send();
                            return;
                        }
                        DB::beginTransaction();
                        try
                        {
                            $absensi = Absensi::create([
                                'karyawan_id' => $record->id,
                                'tanggal' => now(),
                                'jam_masuk' => now(),
                            ]);

                            $absensi->addMedia('storage' . $data['bukti_absen'])
                                ->toMediaCollection('bukti-absensi');

                            DB::commit();

                            Notification::make()
                                ->title('Berhasil')
                                ->body('Bukti absen berhasil diunggah')
                                ->success()
                                ->send();
                        }catch(Exception $e)
                        {
                            DB::rollBack();
                            Notification::make()
                                ->title('Gagal')
                                ->body('Bukti absen gagal diunggah. ' . $e->getMessage())
                                ->danger()
                                ->send();
                            return;
                        }
                        
                    })
            ]);
    }
}
