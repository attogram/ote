<?php

use Illuminate\Database\Seeder;

class WordWordTableSeeder extends Seeder
{
    /**
     * @return void
     */
    public function run()
    {
        $items = [
            [ 1, 1, 11, 2], // one, english, een, dutch
            [ 2, 1, 12, 2],
            [ 3, 1, 13, 2],
            [ 4, 1, 14, 2],
            [ 5, 1, 15, 2],
            [ 6, 1, 16, 2],
            [ 7, 1, 17, 2],
            [ 8, 1, 18, 2],
            [ 9, 1, 19, 2],
            [10, 1, 20, 2], // ten, english, tien, dutch

        ];
        foreach ($items as $item) {
            try {
                DB::table('word_word')->insert(
                    [
                        'source_word_id'     => $item[0],
                        'source_language_id' => $item[1],
                        'target_word_id'     => $item[2],
                        'target_language_id' => $item[3], 
                        'status'             => 1
                    ]
                );
            } catch (Throwable $error) {
                DatabaseSeeder::log(
                    'word_word: ERROR: '. $error->getMessage()
                );
            }
        }
        DatabaseSeeder::log('word_word: +' . count($items));
    }
}
