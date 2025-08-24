<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LexicalEntry extends Model
{
    use HasFactory;
    protected $fillable = ['token_id', 'language_id'];

    public function token(): BelongsTo
    {
        return $this->belongsTo(Token::class);
    }

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class);
    }

    public function attributes(): HasMany
    {
        return $this->hasMany(Attribute::class);
    }

    public function links(): HasMany
    {
        return $this->hasMany(Link::class, 'source_lexical_entry_id');
    }

    public function linkedFrom(): HasMany
    {
        return $this->hasMany(Link::class, 'target_lexical_entry_id');
    }
}
