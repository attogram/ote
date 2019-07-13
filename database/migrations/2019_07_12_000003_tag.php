<?php
declare(strict_types = 1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Tag extends Migration
{
    /**
     * @return void
     */
    public function up()
    {
        Schema::create(
            'tag', 
            function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->integer('status')
                    ->default(0)
                    ->signed();
                $table->string('tag')
                    ->unique();
                $table->bigInteger('parent_tag_id')
                    ->unsigned()
                    ->nullable();
            }
        );
    }

    /**
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tag');
    }
}
