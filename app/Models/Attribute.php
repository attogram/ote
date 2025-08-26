<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attribute extends Model
{
    use HasFactory;

    protected $fillable = ['lexical_entry_id', 'key', 'value'];

    public function lexicalEntry(): BelongsTo
    {
        return $this->belongsTo(LexicalEntry::class);
    }
}
