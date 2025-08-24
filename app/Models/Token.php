<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Token extends Model
{
    protected $fillable = ['text'];

    public function lexicalEntries(): HasMany
    {
        return $this->hasMany(LexicalEntry::class);
    }
}
