<?php
declare(strict_types = 1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class WordWord extends Migration
{
    /**
     * @return void
     */
    public function up()
    {
        Schema::create(
            'word_word', 
            function (Blueprint $table) {
                $table->unsignedBigInteger('source_word_id');
                $table->foreign('source_word_id', 'ww_fk_source_word')
                    ->references('id')
                    ->on('word');

                $table->unsignedBigInteger('source_language_id');
                $table->foreign('source_language_id', 'ww_fk_source_language')
                    ->references('id')
                    ->on('language');

                $table->unsignedBigInteger('target_word_id');
                $table->foreign('target_word_id', 'ww_fk_target_word')
                    ->references('id')
                    ->on('word');

                $table->unsignedBigInteger('target_language_id');
                $table->foreign('target_language_id', 'ww_fk_target_language')
                    ->references('id')
                    ->on('language');

                $table->integer('status')
                    ->default(0)
                    ->signed();

                $table->primary(
                    [
                        'source_word_id', 
                        'source_language_id', 
                        'target_word_id', 
                        'target_language_id'
                    ],
                    'word_word_primary'
                );
            }
        );
    }

    /**
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('word_word');
    }
}
