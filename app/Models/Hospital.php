<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Hospital extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'city', 'contact_person', 'email', 'phone', 'address'];

    public function administrationLetters(): HasMany
    {
        return $this->hasMany(AdministrationLetter::class);
    }

    public function checklistItems(): HasMany
    {
        return $this->hasMany(HospitalChecklistItem::class)->orderBy('sort_order');
    }
}
