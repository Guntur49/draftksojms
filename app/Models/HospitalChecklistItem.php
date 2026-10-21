<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HospitalChecklistItem extends Model
{
    use HasFactory;

    public const STARTER_DOCUMENTS = [
        'Legalitas NMS - Akta perubahan terbaru',
        'Surat Penunjukan NMS kepada Legal Officer',
        'Surat Penunjukan NMS kepada JMS',
        'Legalitas JMS - Akta perubahan terbaru',
        'Surat Penunjukan JMS kepada Marketing',
        'Surat Penawaran',
        'Purchase Order (PO)',
        'Feasibility Study (FS) - data waiting list pasien, data dari Dinas Kesehatan dan/atau BPJS, dan data pendukung lain',
        'Layout penempatan/perencanaan yang telah ditandatangani atau disetujui oleh pihak rumah sakit',
        'Perjanjian KSO asli yang telah ditandatangani para pihak, berikut seluruh lampiran',
        'Daftar nama perawat/tenaga terkait KSO',
        'Legalitas Rumah Sakit',
    ];

    protected $fillable = [
        'hospital_id',
        'document_name',
        'is_available',
        'notes',
        'sort_order',
    ];

    protected function casts(): array
    {
        return ['is_available' => 'boolean'];
    }

    public function hospital(): BelongsTo
    {
        return $this->belongsTo(Hospital::class);
    }

    public function administrationLetters(): HasMany
    {
        return $this->hasMany(AdministrationLetter::class, 'checklist_item_id')
            ->whereNotNull('pdf_path')
            ->latest();
    }
}
