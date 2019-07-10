<?php
/**
 * Open Translation Engine
 * Base Database Tables
 */
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
                $table->integer('status')->default(0);
                $table->string('word')->unique();
            }
        );

        Schema::create(
            'language', 
            function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->integer('status')->default(0);
                $table->string('name')->unique();
                $table->string('code')->unique();
            }
        );

        Schema::create(
            'tag', 
            function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->integer('status')->default(0);
                $table->string('tag')->unique();
                $table->integer('parent_tag_id');
            }
        );

        Schema::create(
            'meta', 
            function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->integer('status')->default(0);
                $table->longText('meta')->unique();
            }
        );

        Schema::create(
            'media', 
            function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->integer('status')->default(0);
                $table->string('name');
                $table->string('url');
                $table->string('type');
            }
        );

        Schema::create(
            'log', 
            function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->dateTime('datetime');
                $table->string('event');
                $table->integer('user_id')->default(0);
            }
        );

        Schema::create(
            'word_word', 
            function (Blueprint $table) {
                $table->integer('source_word_id');
                $table->foreign('source_word_id')->references('word')->on('id');
                $table->integer('source_language_id');
                $table->foreign('source_language_id')->references('language')->on('id');
                $table->integer('target_word_id');
                $table->foreign('target_word_id')->references('word')->on('id');
                $table->integer('target_language_id');
                $table->foreign('target_language_id')->references('language')->on('id');
                $table->primary(['source_word_id', 'source_language_id', 'target_word_id', 'target_language_id']);
                $table->integer('status')->default(0);
            }
        );
        
        Schema::create(
            'language_word', 
            function (Blueprint $table) {
                $table->integer('word_id');
                $table->foreign('word_id')->references('word')->on('id');
                $table->integer('language_id');
                $table->foreign('language_id')->references('language')->on('id');
                $table->primary(['word_id', 'language_id']);
                $table->integer('status')->default(0);
            }
        );

        Schema::create(
            'tag_word', 
            function (Blueprint $table) {
                $table->integer('word_id');
                $table->foreign('word_id')->references('word')->on('id');
                $table->integer('language_id');
                $table->foreign('language_id')->references('language')->on('id');
                $table->integer('tag_id');
                $table->foreign('tag_id')->references('tag')->on('id');
                $table->primary(['word_id', 'language_id', 'tag_id']);
                $table->integer('status')->default(0);
            }
        );

        Schema::create(
            'meta_word', 
            function (Blueprint $table) {
                $table->integer('word_id');
                $table->foreign('word_id')->references('word')->on('id');
                $table->integer('language_id');
                $table->foreign('language_id')->references('language')->on('id');
                $table->integer('meta_id');
                $table->foreign('meta_id')->references('meta')->on('id');
                $table->primary(['word_id', 'language_id', 'meta_id']);
                $table->integer('status')->default(0);
            }
        );

        Schema::create(
            'media_word', 
            function (Blueprint $table) {
                $table->integer('word_id');
                $table->foreign('word_id')->references('word')->on('id');
                $table->integer('language_id');
                $table->foreign('language_id')->references('language')->on('id');
                $table->integer('media_id');
                $table->foreign('media_id')->references('media')->on('id');
                $table->primary(['word_id', 'language_id', 'media_id']);
                $table->integer('status')->default(0);
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
