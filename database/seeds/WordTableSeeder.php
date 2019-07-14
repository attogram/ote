<?php

use Illuminate\Database\Seeder;

class WordTableSeeder extends Seeder
{
    /**
     * @return void
     */
    public function run()
    {
        $words = [
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
        ];
        foreach ($words as $word) {
            DB::table('word')->insert(
                [
                    'id'     => $word[0],
                    'word'   => $word[1], 
                    'status' => 1
                ]
            );
        }
        DatabaseSeeder::log('Word Table Seeder: +' . count($words));
    }
}
