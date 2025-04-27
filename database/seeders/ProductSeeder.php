<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $faker = \Faker\Factory::create();
        $productos = [
            [
                'codigo' => '12314214',
                'nombre' => 'Coca Cola 300ml',
                'cantidad_unidades' => 90,
                'cantidad_bultos' => 15,
                'bultos_min_aviso' => 5,
                'cantidad_por_bulto' => 6,
                'precio_compra_unitario' => 80.00,
                'precio_compra_bulto' => 480.00,
                'precio_venta_unitario' => 120.00,
            ],
            [
                'codigo' => '98237492',
                'nombre' => 'Pepsi 500ml',
                'cantidad_unidades' => 60,
                'cantidad_bultos' => 10,
                'bultos_min_aviso' => 3,
                'cantidad_por_bulto' => 6,
                'precio_compra_unitario' => 75.00,
                'precio_compra_bulto' => 450.00,
                'precio_venta_unitario' => 110.00,
            ],
            [
                'codigo' => '37482910',
                'nombre' => 'Agua Mineral 1L',
                'cantidad_unidades' => 120,
                'cantidad_bultos' => 20,
                'bultos_min_aviso' => 5,
                'cantidad_por_bulto' => 6,
                'precio_compra_unitario' => 50.00,
                'precio_compra_bulto' => 300.00,
                'precio_venta_unitario' => 80.00,
            ],
            [
                'codigo' => '83726190',
                'nombre' => 'Yerba Mate Taragüi 1kg',
                'cantidad_unidades' => 144,
                'cantidad_bultos' => 12,
                'bultos_min_aviso' => 4,
                'cantidad_por_bulto' => 12,
                'precio_compra_unitario' => 600.00,
                'precio_compra_bulto' => 7200.00,
                'precio_venta_unitario' => 750.00,
            ],
            [
                'codigo' => '62718292',
                'nombre' => 'Aceite Cocinero 900ml',
                'cantidad_unidades' => 120,
                'cantidad_bultos' => 20,
                'bultos_min_aviso' => 6,
                'cantidad_por_bulto' => 6,
                'precio_compra_unitario' => 400.00,
                'precio_compra_bulto' => 2400.00,
                'precio_venta_unitario' => 520.00,
            ],
            [
                'codigo' => '11223344',
                'nombre' => 'Harina 0000 1kg',
                'cantidad_unidades' => 30,
                'cantidad_bultos' => 30,
                'bultos_min_aviso' => 10,
                'cantidad_por_bulto' => 1,
                'precio_compra_unitario' => 120.00,
                'precio_compra_bulto' => 120.00,
                'precio_venta_unitario' => 180.00,
            ],
            [
                'codigo' => '22334455',
                'nombre' => 'Azúcar Ledesma 1kg',
                'cantidad_unidades' => 50,
                'cantidad_bultos' => 50,
                'bultos_min_aviso' => 10,
                'cantidad_por_bulto' => 1,
                'precio_compra_unitario' => 140.00,
                'precio_compra_bulto' => 140.00,
                'precio_venta_unitario' => 190.00,
            ],
            [
                'codigo' => '99887766',
                'nombre' => 'Leche La Serenísima 1L',
                'cantidad_unidades' => 24,
                'cantidad_bultos' => 24,
                'bultos_min_aviso' => 5,
                'cantidad_por_bulto' => 1,
                'precio_compra_unitario' => 180.00,
                'precio_compra_bulto' => 180.00,
                'precio_venta_unitario' => 240.00,
            ],
            [
                'codigo' => '33445566',
                'nombre' => 'Pan de molde Bimbo',
                'cantidad_unidades' => 12,
                'cantidad_bultos' => 12,
                'bultos_min_aviso' => 3,
                'cantidad_por_bulto' => 1,
                'precio_compra_unitario' => 250.00,
                'precio_compra_bulto' => 250.00,
                'precio_venta_unitario' => 320.00,
            ],
            [
                'codigo' => '44556677',
                'nombre' => 'Galletitas Oreo 117g',
                'cantidad_unidades' => 18,
                'cantidad_bultos' => 18,
                'bultos_min_aviso' => 5,
                'cantidad_por_bulto' => 1,
                'precio_compra_unitario' => 200.00,
                'precio_compra_bulto' => 200.00,
                'precio_venta_unitario' => 280.00,
            ],
        ];

        foreach ($productos as $producto) {
            Product::create($producto);
        }
    }
}
