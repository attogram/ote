<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Link extends Model
{
    protected $fillable = ['source_lexical_entry_id', 'target_lexical_entry_id', 'type'];

    public function sourceEntry(): BelongsTo
    {
        return $this->belongsTo(LexicalEntry::class, 'source_lexical_entry_id');
    }

    public function targetEntry(): BelongsTo
    {
        return $this->belongsTo(LexicalEntry::class, 'target_lexical_entry_id');
    }
}
