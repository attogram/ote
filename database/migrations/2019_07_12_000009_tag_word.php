<?php
declare(strict_types = 1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TagWord extends Migration
{
    /**
     * @return void
     */
    public function up()
    {
        Schema::create(
            'tag_word', 
            function (Blueprint $table) {
                $table->unsignedBigInteger('word_id');
                $table->foreign('word_id', 'tw_fk_word')
                    ->references('id')
                    ->on('word');

                $table->unsignedBigInteger('language_id');
                $table->foreign('language_id', 'tw_fk_language')
                    ->references('id')
                    ->on('language');

                $table->unsignedBigInteger('tag_id');
                $table->foreign('tag_id', 'tw_fk_tag')
                    ->references('id')
                    ->on('tag');

                $table->integer('status')
                    ->default(0)
                    ->signed();

                $table->primary(
                    [
                        'word_id', 
                        'language_id', 
                        'tag_id'
                    ],
                    'tag_word_primary'
                );
            }
        );
    }

    /**
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tag_word');
    }
}
