<?php

namespace App\Console\Commands;

use App\Models\Link;
use Illuminate\Console\Command;

class DeleteLink extends Command
{
    protected $signature = 'ote:delete-link {id : The ID of the link to delete}';

    protected $description = 'Deletes a link.';

    public function handle()
    {
        $id = $this->argument('id');
        $link = Link::find($id);

        if (!$link) {
            $this->error("Link with ID '{$id}' not found.");
            return 1;
        }

        $link->delete();

        $this->info("Link with ID '{$id}' has been deleted.");
    }
}
