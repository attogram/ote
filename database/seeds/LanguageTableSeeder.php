<?php

use Illuminate\Database\Seeder;

class LanguageTableSeeder extends Seeder
{
    /**
     * @return void
     */
    public function run()
    {
        $languages = [
            [ 1, 'English',             'eng'], //
            [ 2, 'Dutch',               'nld'], // Nederlands
            [ 3, 'German',              'deu'], // Deutsch
            [ 4, 'French',              'fre'], // Français
            [ 5, 'Spanish',             'spa'], // Español
            [ 6, 'Italian',             'ita'], // Italiano
            [ 7, 'Portugese',           'por'], // Português
            [ 8, 'Russian',             'rus'], // Pусский
            [ 9, 'Ukranian',            'ukr'], // Yкраїнська
            [10, 'Japanese',            'jpn'], // 日本語
            [11, 'Indonesian',          'ind'], // Bahasa Indonesia
            [12, 'Batak',               'btk'], // Toba–Batak
            [13, 'Hindi',               'hin'], // हिन्दी
            [14, 'Tagalog',             'tgl'], // 
            [15, 'Filipino',            'fil'], // Filipino 
            [16, 'Latin',               'lat'], // Latīna
            [17, 'Proto-Indo-European', 'ine'], //
            [18, 'Ancient-Greek',       'grc'], // Ἑλληνική
            [19, 'Modern-Greek',        'ell'], // Νέα Ελληνικά
            [20, 'Mandarin-Chinese',    'cmn'], // 官话
            [21, 'Cantonese-Chinese',   'yue'], // 廣東話
            [22, 'Minnan-Chinese',      'nan'], // 閩南語
            [23, 'Esperanto',           'epo'], // Esperanto
            [24, 'Interlingua',         'ina'], // Interlingua
            [25, 'Interglossa',         'igs'], // Interglossa
            [26, 'Volapük',             'vol'], // Volapük
            [27, 'Lojban',              'jbo'], // .lojban.
            [28, 'Klingon',             'tlh'], // tlhIngan Hol
            [29, 'International-Sign',  'ils'], // 
            [30, 'American-Sign',       'ase'], // 
            [31, 'Emoji',               'emo'], //
        ];
        foreach ($languages as $language) {
            DB::table('language')->insert(
                [
                    'id'     => $language[0],
                    'name'   => $language[1], 
                    'code'   => $language[2], 
                    'status' => 1
                ]
            );
        }
    }
}
