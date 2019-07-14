<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(LanguageTableSeeder::class);
        $this->call(WordTableSeeder::class);
        $this->call(TagTableSeeder::class);
    }

    /**
     * @param string $message
     */
    public static function log(string $message)
    {
        DB::table('log')->insert(
            [
                'event'   => $message,
                'user_id' => 0,
            ]
        );
    }
}
