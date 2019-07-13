<?php
declare(strict_types = 1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Meta extends Migration
{
    /**
     * @return void
     */
    public function up()
    {
        Schema::create(
            'meta', 
            function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->integer('status')
                    ->default(0)
                    ->signed();
                $table->string('meta')
                    ->unique();
            }
        );
    }

    /**
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('meta');
    }
}
