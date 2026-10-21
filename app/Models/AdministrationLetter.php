<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdministrationLetter extends Model
{
    use HasFactory;

    protected $fillable = [
        'hospital_id',
        'checklist_item_id',
        'master_letter_id',
        'document_name',
        'document_number',
        'status',
        'pdf_path',
        'variables',
        'content_snapshot',
    ];

    protected function casts(): array
    {
        return ['variables' => 'array'];
    }

    public function hospital(): BelongsTo
    {
        return $this->belongsTo(Hospital::class);
    }

    public function masterLetter(): BelongsTo
    {
        return $this->belongsTo(MasterLetter::class);
    }

    public function checklistItem(): BelongsTo
    {
        return $this->belongsTo(HospitalChecklistItem::class, 'checklist_item_id');
    }
}
