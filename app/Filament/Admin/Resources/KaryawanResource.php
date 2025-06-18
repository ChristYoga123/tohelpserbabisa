<?php

namespace App\Filament\Admin\Resources;

use Exception;
use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use App\Models\Cabang;
use App\Models\Karyawan;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Components\Grid;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Admin\Resources\KaryawanResource\Pages;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use App\Filament\Admin\Resources\KaryawanResource\RelationManagers;

class KaryawanResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Karyawan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make()
                    ->columns(1)
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama Karyawan')
                            ->required()
                            ->unique(ignoreRecord: true),
                        TextInput::make('email')
                            ->label('Email')
                            ->required()
                            ->email()
                            ->unique(ignoreRecord: true),
                        Select::make('cabang_id')
                            ->label('Cabang')
                            ->options(Cabang::all()->pluck('nama', 'id'))
                            ->required(),
                        TextInput::make('password')
                            ->password()
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $operation): bool => $operation === 'create'),
                        DatePicker::make('tanggal_lahir')
                            ->label('Tanggal Lahir')
                            ->required(fn(string $operation): bool => $operation === 'create')
                            ->locale('id'),
                        FileUpload::make('avatar_url')
                            ->label('Foto')
                            ->maxFiles(1),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(User::query()->with('media')->whereNot('name', 'Admin')->whereHas('roles', fn (Builder $query) => $query->where('name', 'karyawan')))
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Karyawan')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('cabang.nama')
                    ->label('Cabang')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tanggal_lahir')
                    ->label('Tanggal Lahir')
                    ->searchable()
                    ->sortable()
                    ->getStateUsing(function (User $user)
                    {
                        // ambil usia dari tanggal lahir
                        $tanggal_lahir = $user?->custom_fields['tanggal_lahir'] ?? null;
                        $usia = date_diff(date_create($tanggal_lahir), date_create('now'))->y;
                        return $usia . ' tahun';
                    }),
                ImageColumn::make('avatar_url')
                    ->label('Foto'),
            ])
            ->filters([
                SelectFilter::make('cabang_id')
                    ->label('Cabang')
                    ->options(Cabang::all()->pluck('nama', 'id')),
            ], layout: FiltersLayout::AboveContent)
            ->actions([
                Tables\Actions\Action::make('lihatAbsensi')
                    ->label('Lihat Absensi')
                    ->color('info')
                    ->icon('heroicon-o-document')
                    ->url(fn(User $karyawan) => Pages\LihatAbsensiPage::getUrl(['record' => $karyawan])),
                Tables\Actions\EditAction::make()
                    ->form([
                        Grid::make()
                        ->columns(1)
                        ->schema([
                            TextInput::make('name')
                                ->label('Nama Karyawan')
                                ->required()
                                ->unique(ignoreRecord: true),
                            TextInput::make('email')
                                ->label('Email')
                                ->required()
                                ->email()
                                ->unique(ignoreRecord: true),
                            TextInput::make('password')
                                ->password(),
                            DatePicker::make('tanggal_lahir')
                                ->label('Tanggal Lahir')
                                ->required(fn(string $operation): bool => $operation === 'create')
                                ->locale('id')
                                ->formatStateUsing(fn(User $user) => $user?->custom_fields['tanggal_lahir'] ?? null),
                            FileUpload::make('avatar_url')
                                ->label('Foto')
                                ->maxFiles(1),
                        ]),
                    ])
                    ->using(function(User $user, array $data)
                    {
                        // dd($data);
                        DB::beginTransaction();
                        try
                        {
                            $user->update([
                                'name' => $data['name'],
                                'email' => $data['email'],
                                'avatar_url' => $data['avatar_url'],
                                'cabang_id' => $data['cabang_id'],
                            ]);

                            if(isset($data['password']))
                            {
                                $user->update([
                                    'password' => Hash::make($data['password']),
                                ]);
                            }

                            if(isset($data['tanggal_lahir']))
                            {
                                $user->update([
                                    'custom_fields' => [
                                        'tanggal_lahir' => $data['tanggal_lahir'],
                                    ],
                                ]);
                            }

                            $user->syncRoles(['karyawan']);
        
                            DB::commit();

                            Notification::make()
                                ->title('Sukses!')
                                ->body('Edit karyawan berhasil!')
                                ->success()
                                ->send();
                        } catch(Exception $e)
                        {
                            DB::rollBack();

                            Notification::make()
                                ->title('Gagal!')
                                ->body('Edit karyawan gagal!' . $e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageKaryawans::route('/'),
            'absen' => Pages\LihatAbsensiPage::route('/{record}/absen'),
        ];
    }
}
