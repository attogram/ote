<?php
declare(strict_types = 1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Log extends Migration
{
    /**
     * @return void
     */
    public function up()
    {
        Schema::create(
            'log', 
            function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->timestamp('time')->useCurrent();
                $table->text('event');
                $table->bigInteger('user_id')
                    ->default(0)
                    ->unsigned();
            }
        );
    }

    /**
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('log');
    }
}
