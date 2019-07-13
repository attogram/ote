<?php
declare(strict_types = 1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class LanguageWord extends Migration
{
    /**
     * @return void
     */
    public function up()
    {
        Schema::create(
            'language_word', 
            function (Blueprint $table) {
                $table->unsignedBigInteger('word_id');
                $table->foreign('word_id', 'lw_fk_word')
                    ->references('id')
                    ->on('word');

                $table->unsignedBigInteger('language_id');
                $table->foreign('language_id', 'lw_fk_language')
                    ->references('id')
                    ->on('language');

                $table->integer('status')
                    ->default(0)
                    ->signed();

                $table->primary(
                    [
                        'word_id', 
                        'language_id'
                    ],
                    'language_word_primary'
                );
            }
        );
    }

    /**
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('language_word');
    }
}
