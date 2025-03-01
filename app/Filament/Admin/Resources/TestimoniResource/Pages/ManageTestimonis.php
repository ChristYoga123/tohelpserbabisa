<?php

namespace App\Filament\Admin\Resources\TestimoniResource\Pages;

use App\Filament\Admin\Resources\TestimoniResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Contracts\Support\Htmlable;

class ManageTestimonis extends ManageRecords
{
    protected static string $resource = TestimoniResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTitle(): string|Htmlable
    {
        return 'Testimoni';
    }
}
