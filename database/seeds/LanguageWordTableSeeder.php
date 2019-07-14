<?php

use Illuminate\Database\Seeder;

class LanguageWordTableSeeder extends Seeder
{
    /**
     * @return void
     */
    public function run()
    {
        $items = [
            // 1 = eng / English
            [ 1, 1],
            [ 2, 1],
            [ 3, 1],
            [ 4, 1],
            [ 5, 1],
            [ 6, 1],
            [ 7, 1],
            [ 8, 1],
            [ 9, 1],
            [10, 1],
            // 2 = nld / Dutch
            [11, 2],
            [12, 2],
            [13, 2],
            [14, 2],
            [15, 2],
            [16, 2],
            [17, 2],
            [18, 2],
            [19, 2],
            [20, 2],
            //[21, 2], 
        ];
        foreach ($items as $item) {
            try {
                DB::table('language_word')->insert(
                    [
                        'word_id'     => $item[0],
                        'language_id' => $item[1], 
                        'status'      => 1
                    ]
                );
            } catch (Throwable $error) {
                DatabaseSeeder::log(
                    'language_word: ERROR: ' . $error->getMessage()
                );
            }
        }
        DatabaseSeeder::log('language_word: +' . count($items));
    }
}
