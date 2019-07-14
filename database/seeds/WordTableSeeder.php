<?php

use Illuminate\Database\Seeder;

class WordTableSeeder extends Seeder
{
    /**
     * @return void
     */
    public function run()
    {
        $items = [
            [ 1, 'one'],
            [ 2, 'two'],
            [ 3, 'three'],
            [ 4, 'four'],
            [ 5, 'five'],
            [ 6, 'six'],
            [ 7, 'seven'],
            [ 8, 'eight'],
            [ 9, 'nine'],
            [10, 'ten'],
            [11, 'een'],
            [12, 'twee'],
            [13, 'drie'],
            [14, 'vier'],
            [15, 'vijf'],
            [16, 'zes'],
            [17, 'zeven'],
            [18, 'acht'],
            [19, 'negen'],
            [20, 'tien'],
            [21, 'één'], // @TODO - setup correct charset/collation
        ];
        foreach ($items as $item) {
            try {
                DB::table('word')->insert(
                    [
                        'id'     => $item[0],
                        'word'   => $item[1], 
                        'status' => 1
                    ]
                );
            } catch (Throwable $error) {
                DatabaseSeeder::log(
                    'word: ERROR: '. $error->getMessage()
                );
            }
        }
        DatabaseSeeder::log('word: +' . count($items));
    }
}
