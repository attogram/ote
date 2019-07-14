<?php

use Illuminate\Database\Seeder;

class LanguageTableSeeder extends Seeder
{
    /**
     * @return void
     */
    public function run()
    {
        $items = [
            [ 1, 'eng', 'English',       'English'], 
            [ 2, 'nld', 'Dutch',         'Nederlands'], 
            [ 3, 'deu', 'German',        'Deutsch'], 
            [ 4, 'fre', 'French',        'Français'], 
            [ 5, 'spa', 'Spanish',       'Español'], 
            [ 6, 'ita', 'Italian',       'Italiano'],
            [ 7, 'por', 'Portugese',     'Português'],
            [ 8, 'rus', 'Russian',       'Pусский'],
            [ 9, 'ukr', 'Ukranian',      'Yкраїнська'],
            [10, 'jpn', 'Japanese',      '日本語'],
            [11, 'ind', 'Indonesian',    'Bahasa Indonesia'],
            [12, 'btk', 'Batak',         'Toba Batak'], 
            [13, 'hin', 'Hindi',         'हिन्दी'], 
            [14, 'tgl', 'Tagalog',       'Tagalog'], 
            [15, 'fil', 'Filipino',      'Filipino'],  
            [16, 'lat', 'Latin',         'Latīna'],  
            [17, 'ine', 'Proto-Indo-European', 'Proto-Indo-European'], 
            [18, 'grc', 'Ancient Greek', 'Ἑλληνική'],
            [19, 'ell', 'Modern Greek',  'Νέα Ελληνικά'],
            [20, 'cmn', 'Mandarin',      '官话'],
            [21, 'yue', 'Cantonese',     '廣東話'],
            [22, 'nan', 'Minnan',        '閩南語'],
            [23, 'epo', 'Esperanto',     'Esperanto'],
            [24, 'ina', 'Interlingua',   'Interlingua'],
            [25, 'igs', 'Interglossa',   'Interglossa'],
            [26, 'vol', 'Volapük',       'Volapük'],
            [27, 'jbo', 'Lojban',        'lojban'],
            [28, 'tlh', 'Klingon',       'tlhIngan Hol'],
            [31, 'emo', 'Emoji',         '😃'],
        ];
        foreach ($items as $item) {
            try {
                DB::table('language')->insert(
                    [
                        'id'        => $item[0],
                        'code'      => $item[1], 
                        'name'      => $item[2], 
                        'name_self' => $item[3], 
                        'status'    => 1
                    ]
                );
            } catch (Throwable $error) {
                DatabaseSeeder::log(
                    'language: ERROR: ' . $error->getMessage()
                );
            }
        }
        DatabaseSeeder::log('language: +' . count($items));
    }
}
