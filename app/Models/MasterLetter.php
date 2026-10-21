<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MasterLetter extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'category', 'description', 'body'];

    public function administrationLetters(): HasMany
    {
        return $this->hasMany(AdministrationLetter::class);
    }

    /** @return list<string> */
    public function variables(): array
    {
        preg_match_all('/{{\s*([a-zA-Z][a-zA-Z0-9_]*)\s*}}/', $this->body, $matches);

        return array_values(array_unique($matches[1]));
    }
}
