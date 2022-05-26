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
              'name' => 'EL BARRIL',
              'location' => 'Cra 4 # 2-16',
              'description' => 'Bar venta de bebidas frias y calientas servicio de consumo en la mesa',
              'cellphone' => '3505754364',
              'email' => 'info@elbarril.com',
              'logo_url' => 'http:/localhost:8090/src/pdf/nombre-tienda/logo.png',
              'menu_url' => 'http:/localhost:8090/src/pdf/nombre-tienda/menu.pdf',
              'category_id' => 2
            ],
            [
              'name' => 'BARBAOS',
              'location' => 'Cl 3 # 5-13',
              'description' => 'Cafe Bar venta de bebidas alcoholicas servicio en la mesa y a domicilio',
              'cellphone' => '3143729083',
              'email' => 'info@barbaos.com',
              'logo_url' => 'http:/localhost:8090/src/pdf/nombre-tienda/logo.png',
              'menu_url' => 'http:/localhost:8090/src/pdf/nombre-tienda/menu.pdf',
              'category_id' => 2
            ],
            [
              'name' => 'DEL RANCHO',
              'location' => 'Vereda la Plazuela, CL 3 # 7-23',
              'description' => 'Local de comidas rapidas con servicio en la mesa',
              'cellphone' => '3226197268',
              'email' => 'info@delrancho.com',
              'logo_url' => 'http:/localhost:8090/src/pdf/nombre-tienda/logo.png',
              'menu_url' => 'http:/localhost:8090/src/pdf/nombre-tienda/menu.pdf',
              'category_id' => 3
            ],
            [
              'name' => 'EL PUNTO DE LA AREPA',
              'location' => 'Sector, el Cascajal',
              'description' => 'Local de venta de arepas rellenas, servicio en la mesa y a domicilio',
              'cellphone' => '3204413777',
              'email' => 'info@puntodelaarepa.com',
              'logo_url' => 'http:/localhost:8090/src/pdf/nombre-tienda/logo.png',
              'menu_url' => 'http:/localhost:8090/src/pdf/nombre-tienda/menu.pdf',
              'category_id' => 3
            ],
            [
              'name' => 'PIZZERIA CANA',
              'location' => 'CL 4 # 5-96',
              'description' => 'Pizzeria y venta de comidas rapidas, ademas cuenta con servicio a domicilio',
              'cellphone' => '3145647890',
              'email' => 'info@cana.com',
              'logo_url' => 'http:/localhost:8090/src/pdf/nombre-tienda/logo.png',
              'menu_url' => 'http:/localhost:8090/src/pdf/nombre-tienda/menu.pdf',
              'category_id' => 3
            ]
            ,[
              'name' => 'LOS SAUCES',
              'location' => 'a 2-74, Cra 9 #32',
              'description' => 'Piqueteadero Campestre tipo Granja, ofrece picadas, platos a la carta, postres ademas de 				que permite la interaccion con animales de granja',
              'cellphone' => '3138510936',
              'email' => 'info@lossauces.com',
              'logo_url' => 'http:/localhost:8090/src/pdf/nombre-tienda/logo.png',
              'menu_url' => 'http:/localhost:8090/src/pdf/nombre-tienda/menu.pdf',
              'category_id' => 1
            ],
            [
              'name' => 'EL ORILLO',
              'location' => 'Km 12 Via Ubate Zipauquira',
              'description' => 'Restaurante con exquisitos platos a la carata, atencion en la mesa y servicio a domicilio',
              'cellphone' => '3123490029',
              'email' => 'info@elorillo.com',
              'logo_url' => 'http:/localhost:8090/src/pdf/nombre-tienda/logo.png',
              'menu_url' => 'http:/localhost:8090/src/pdf/nombre-tienda/menu.pdf',
              'category_id' => 1
            ],
            [
              'name' => 'EL PINO',
              'location' => 'Carrera 4 # 0-61',
              'description' => 'Piqueteadero Campestre, ofrece picadas desde $50.000, sopas, platos a la carta y postres',
              'cellphone' => '3212908765',
              'email' => 'info@elpino.com',
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
