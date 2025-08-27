<?php

namespace App\Console\Commands;

use App\Models\Attribute;
use Illuminate\Console\Command;

class ListAttributes extends Command
{
    protected $signature = 'ote:list-attributes';

    protected $description = 'Lists all attributes.';

    public function handle()
    {
        $attributes = Attribute::with('lexicalEntry')->get()->map(function ($attribute) {
            return [
                'ID' => $attribute->id,
                'Lexical Entry ID' => $attribute->lexical_entry_id,
                'Key' => $attribute->key,
                'Value' => $attribute->value,
            ];
        });

        $this->table(['ID', 'Lexical Entry ID', 'Key', 'Value'], $attributes);
    }
}
