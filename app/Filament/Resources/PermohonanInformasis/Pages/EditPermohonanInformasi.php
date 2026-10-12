<?php

namespace App\Filament\Resources\PermohonanInformasis\Pages;

use App\Filament\Resources\PermohonanInformasis\PermohonanInformasiResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPermohonanInformasi extends EditRecord
{
    protected static string $resource = PermohonanInformasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        $record = $this->getRecord();
        $statusSlug = $record->status?->slug;

        if (in_array($statusSlug, ['dijawab', 'ditolak', 'selesai'])) {
            if (!$record->tanggal_dijawab) {
                $record->update(['tanggal_dijawab' => now()]);
            }

            try {
                \Illuminate\Support\Facades\Mail::to($record->email)
                    ->send(new \App\Mail\PermohonanJawabanMail($record));
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning("Gagal mengirim email jawaban: {$e->getMessage()}");
            }
        }
    }
}
