<?php

use Illuminate\Database\Seeder;

class TagTableSeeder extends Seeder
{
    /**
     * @return void
     */
    public function run()
    {
        $tags = [
            [100, 'noun',               0],
            [101, 'pronoun',          100],
            [102, 'noun-proper',      100],
            [103, 'noun-count',       100],
            [104, 'noun-mass',        100],
            [105, 'noun-collective',  100],
            [106, 'noun-concrete',    100],
            [107, 'noun-abstract',    100],
            [108, 'noun-alienable',   100],
            [109, 'noun-inalienable', 100],
            [110, 'noun-singular',    100],
            [111, 'noun-plural',      100],
            [112, 'noun-compound',    100],

            [200, 'verb',                     0],
            [201, 'verb-intransitive',      200],
            [202, 'verb-transitive',        200],
            [203, 'verb-ditransitive',      200],
            [204, 'verb-double-transitive', 200],
            [205, 'verb-copular',           200],
            [206, 'verb-singular',          200],
            [207, 'verb-plural',            200],
            [208, 'verb-auxiliary',         200],
            [209, 'verb-compound',          200],
            [210, 'verb-finite',            200],
            [211, 'verb-nonfinite',         200],
            [212, 'verb-infinitive',        200],
            [213, 'verb-modal',             200],
            [214, 'verb-separable',         200],

            [300, 'adjective',            0],
            [301, 'adjective-singular', 300],
            [302, 'adjective-plural',   300],

            [400, 'adverb',               0],
            [401, 'adverb-conjunctive', 400],
            [402, 'adverb-flat',        400],
            [403, 'adverb-locative',    400],
            [404, 'adverb-pronominal',  400],

            [500, 'conjunction',                 0],
            [501, 'conjunction-coordinating',  500],
            [502, 'conjunction-correlative',   500],
            [503, 'conjunction-subordinating', 500],

            [600, 'iterjection',             0],
            [601, 'iterjection-volitive',  600],
            [602, 'iterjection-emotive',   600],
            [603, 'iterjection-cognitive', 600],
            [604, 'iterjection-primary',   600],
            [605, 'iterjection-secondary', 600],

            [700, 'article',              0],
            [701, 'article-definite',   700],
            [702, 'article-indefinite', 700],
            [703, 'article-proper',     700],
            [704, 'article-partive',    700],
            [705, 'article-negative',   700],
            [706, 'article-numbered',   700],

            [800, 'adposition',                 0],
            [801, 'adposition-preposition',    800],
            [802, 'adposition-postposition',   800],
            [803, 'adposition-circumposition', 800],
            [804, 'adposition-inposition',     800],
            [805, 'adposition-simple',         800],
            [806, 'adposition-complex',        800],

            [900, 'expletive', 0],
            [901, 'particle', 0],

            [1000, 'tense',                   0],
            [1001, 'tense-present',        1000],
            [1002, 'tense-past',           1000],
            [1003, 'tense-future',         1000],
            [1004, 'tense-imperfect',      1000],
            [1005, 'tense-perfect',        1000],
            [1006, 'tense-pluperfect',     1000],
            [1007, 'tense-future-perfect', 1000],
            [1008, 'tense-relative',       1000],
            [1009, 'tense-absolute',       1000],

            [1100, 'case',                 0],
            [1101, 'case-nominative',   1100],
            [1102, 'case-accusitive',   1100],
            [1103, 'case-dative',       1100],
            [1104, 'case-ablative',     1100],
            [1105, 'case-gentive',      1100],
            [1106, 'case-vocative',     1100],
            [1107, 'case-locative',     1100],
            [1108, 'case-instrumental', 1100],
            [1109, 'case-oblique',      1100],

            [1200, 'number',                 0],
            [1201, 'number-singular',     1200],
            [1202, 'number-plural',       1200],
            [1203, 'number-singulative',  1200],
            [1204, 'number-colletive',    1200],
            [1205, 'number-dual',         1200],
            [1206, 'number-trial',        1200],
            [1207, 'number-quadral',      1200],
            [1208, 'number-paucal',       1200],
            [1209, 'number-distributive', 1200],

            [1300, 'gender',              0],
            [1301, 'gender-masculine', 1300],
            [1302, 'gender-feminine',  1300],
            [1303, 'gender-neutral',   1300],
            [1304, 'gender-animate',   1300],
            [1305, 'gender-inanimate', 1300],
            [1306, 'gender-common',    1300],

            [1400, 'mood',                  0],
            [1401, 'mood-indicative',    1400],
            [1402, 'mood-subjunctive',   1400],
            [1403, 'mood-conditional',   1400],
            [1404, 'mood-optative',      1400],
            [1405, 'mood-imperative',    1400],
            [1406, 'mood-jussive',       1400],
            [1407, 'mood-potential',     1400],
            [1408, 'mood-hypothetical',  1400],
            [1409, 'mood-inferential',   1400],
            [1410, 'mood-interrogative', 1400],
            [1411, 'mood-deontic',       1400],
            [1412, 'mood-epistemic',     1400],
        ];
        foreach ($tags as $tag) {
            DB::table('tag')->insert(
                [
                    'id'            => $tag[0],
                    'tag'           => $tag[1],
                    'parent_tag_id' => $tag[2],
                    'status'        => 1
                ]
            );
        }
        DatabaseSeeder::log('Tag Table Seeder: +' . count($tags));
    }
}
