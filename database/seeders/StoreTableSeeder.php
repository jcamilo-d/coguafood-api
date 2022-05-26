<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Store;

class StoreTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dataToSeed = array(
            [
              'name' => 'NOMBRE TIENDA',
              'location' => 'Cra: test # 1-65',
              'description' => 'Descripción de la tienda',
              'cellphone' => '3123456789',
              'email' => 'emailtienda@mail.com',
              'logo_url' => 'http:/localhost:8090/src/pdf/nombre-tienda/logo.png',
              'menu_url' => 'http:/localhost:8090/src/pdf/nombre-tienda/menu.pdf',
              'category_id' => 1
            ]
        );

        foreach ($dataToSeed as $store) {
            Store::create($store);
        }
    }
}
