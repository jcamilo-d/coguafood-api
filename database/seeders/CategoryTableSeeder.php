<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dataToSeed = array(
            ['name' => 'RESTAURANTES'],
            ['name' => 'BARES'],
            ['name' => 'COMIDAS RÁPIDAS']
        );

        foreach ($dataToSeed as $category) {
            Category::create($category);
        }
    }
}
