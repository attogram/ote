<?php

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Language::firstOrCreate(['code' => 'en', 'name' => 'English']);
        Language::firstOrCreate(['code' => 'es', 'name' => 'Spanish']);
        Language::firstOrCreate(['code' => 'fr', 'name' => 'French']);
    }
}
