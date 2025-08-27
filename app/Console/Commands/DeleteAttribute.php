<?php

namespace App\Console\Commands;

use App\Models\Attribute;
use Illuminate\Console\Command;

class DeleteAttribute extends Command
{
    protected $signature = 'ote:delete-attribute {id : The ID of the attribute to delete}';

    protected $description = 'Deletes an attribute.';

    public function handle()
    {
        $id = $this->argument('id');
        $attribute = Attribute::find($id);

        if (!$attribute) {
            $this->error("Attribute with ID '{$id}' not found.");
            return 1;
        }

        $attribute->delete();

        $this->info("Attribute with ID '{$id}' has been deleted.");
    }
}
