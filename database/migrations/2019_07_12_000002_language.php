<?php
declare(strict_types = 1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Language extends Migration
{
    /**
     * @return void
     */
    public function up()
    {
        Schema::create(
            'language', 
            function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->integer('status')
                    ->default(0)
                    ->signed();
                $table->string('name')
                    ->unique();
                $table->string('code')
                    ->unique();
            }
        );
    }

    /**
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('language');
    }
}
