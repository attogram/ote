<?php
declare(strict_types = 1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MediaWord extends Migration
{
    /**
     * @return void
     */
    public function up()
    {
        Schema::create(
            'media_word', 
            function (Blueprint $table) {
                $table->unsignedBigInteger('word_id');
                $table->foreign('word_id', 'mda_fk_word')
                    ->references('id')
                    ->on('word');
                
                $table->unsignedBigInteger('language_id');
                $table->foreign('language_id', 'mda_fk_language')
                    ->references('id')
                    ->on('language');

                $table->unsignedBigInteger('media_id');
                $table->foreign('media_id', 'mda_fk_media')
                    ->references('id')
                    ->on('media');

                $table->integer('status')
                    ->default(0)
                    ->signed();

                $table->primary(
                    [
                        'word_id', 
                        'language_id', 
                        'media_id'
                    ],
                    'media_word_primary'
                );
            }
        );
    }

    /**
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('media_word');
    }
}
