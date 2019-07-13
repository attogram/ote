<?php
declare(strict_types = 1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MetaWord extends Migration
{
    /**
     * @return void
     */
    public function up()
    {
        Schema::create(
            'meta_word', 
            function (Blueprint $table) {
                $table->unsignedBigInteger('word_id');
                $table->foreign('word_id', 'mtw_fk_word')
                    ->references('id')
                    ->on('word');

                $table->unsignedBigInteger('language_id');
                $table->foreign('language_id', 'mtw_fk_language')
                    ->references('id')
                    ->on('language');

                $table->unsignedBigInteger('meta_id');
                $table->foreign('meta_id', 'mtw_fk_meta')
                    ->references('id')
                    ->on('meta');

                $table->integer('status')
                    ->default(0)
                    ->signed();

                $table->primary(
                    [
                        'word_id', 
                        'language_id', 
                        'meta_id'
                    ],
                    'meta_word_primary'
                );
            }
        );
    }

    /**
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('meta_word');
    }
}
