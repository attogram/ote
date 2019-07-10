<?php
declare(strict_types = 1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class OteBase extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'word', 
            function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->integer('status');
                $table->string('word')->unique();
            }
        );

        Schema::create(
            'language', 
            function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->integer('status');
                $table->string('name')->unique();
                $table->string('code')->unique();
            }
        );

        Schema::create(
            'tag', 
            function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->integer('status');
                $table->string('tag')->unique();
                $table->integer('parent_tag_id');
            }
        );

        Schema::create(
            'meta', 
            function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->integer('status');
                $table->longText('meta')->unique();
            }
        );

        Schema::create(
            'media', 
            function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->integer('status');
                $table->string('name');
                $table->string('url')->unique();
                $table->string('type');
            }
        );

        Schema::create(
            'user', 
            function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('username')->unique();
                $table->string('email');
                $table->integer('level');
            }
        );

        Schema::create(
            'log', 
            function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->dateTime('datetime');
                $table->string('event');
                $table->integer('user_id');
            }
        );

        Schema::create(
            'word_word', 
            function (Blueprint $table) {
                $table->integer('source_word_id');
                $table->integer('source_language_id');
                $table->integer('target_word_id');
                $table->integer('target_language_id');
                $table->primary(
                    [
                        'source_word_id',
                        'source_language_id',
                        'target_word_id',
                        'target_language_id',
                    ]
                );
                $table->integer('status');
            }
        );
        
        Schema::create(
            'language_word', 
            function (Blueprint $table) {
                $table->integer('word_id');
                $table->integer('language_id');
                $table->primary(
                    [
                        'word_id',
                        'language_id',
                    ]
                );
                $table->integer('status');
            }
        );

        Schema::create(
            'tag_word', 
            function (Blueprint $table) {
                $table->integer('word_id');
                $table->integer('language_id');
                $table->integer('tag_id');
                $table->primary(
                    [
                        'word_id',
                        'language_id',
                        'tag_id'
                    ]
                );
                $table->integer('status');
            }
        );

        Schema::create(
            'meta_word', 
            function (Blueprint $table) {
                $table->integer('word_id');
                $table->integer('language_id');
                $table->integer('meta_id');
                $table->primary(
                    [
                        'word_id',
                        'language_id',
                        'meta_id'
                    ]
                );
                $table->integer('status');
            }
        );

        Schema::create(
            'media_word', 
            function (Blueprint $table) {
                $table->integer('word_id');
                $table->integer('language_id');
                $table->integer('media_id');
                $table->primary(
                    [
                        'word_id',
                        'language_id',
                        'media_id'
                    ]
                );
                $table->integer('status');
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('word');
        Schema::dropIfExists('language');
        Schema::dropIfExists('tag');
        Schema::dropIfExists('meta');
        Schema::dropIfExists('media');
        Schema::dropIfExists('user');
        Schema::dropIfExists('log');
        Schema::dropIfExists('word_word');
        Schema::dropIfExists('language_word');
        Schema::dropIfExists('tag_word');
        Schema::dropIfExists('meta_word');
        Schema::dropIfExists('media_word');
    }
}
