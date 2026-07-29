<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Tag;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Kuliah', 'Pribadi', 'Organisasi', 'Kerja'] as $nama) {
            Category::firstOrCreate(['nama_kategori' => $nama]);
        }

        foreach (['Urgent', 'Meeting', 'Revisi'] as $nama) {
            Tag::firstOrCreate(['nama_tag' => $nama]);
        }
    }
}
