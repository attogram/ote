<?php
declare(strict_types = 1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Media extends Migration
{
    /**
     * @return void
     */
    public function up()
    {
        Schema::create(
            'media', 
            function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->integer('status')
                    ->default(0)
                    ->signed();
                $table->string('name');
                $table->string('url');
                $table->string('mime');
            }
        );
    }

    /**
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('media');
    }
}
