<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        Category::create(['name' => 'Llibres de text']);
        Category::create(['name' => 'Llibres de lectura']);
        Category::create(['name' => 'Roba']);
    }
}
