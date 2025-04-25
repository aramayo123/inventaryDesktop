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
                'descripcion' => 'Bebida gaseosa clásica en presentación individual.',
                'stock' => 50,
                'stock_minimo' => 10,
                'precio_compra' => 80.00,
                'precio_venta' => 120.00,
            ],
            [
                'codigo' => '98237492',
                'nombre' => 'Pepsi 500ml',
                'descripcion' => 'Bebida gaseosa sabor cola.',
                'stock' => 40,
                'stock_minimo' => 10,
                'precio_compra' => 75.00,
                'precio_venta' => 110.00,
            ],
            [
                'codigo' => '37482910',
                'nombre' => 'Agua Mineral 1L',
                'descripcion' => 'Agua sin gas en botella plástica.',
                'stock' => 60,
                'stock_minimo' => 15,
                'precio_compra' => 50.00,
                'precio_venta' => 80.00,
            ],
            [
                'codigo' => '83726190',
                'nombre' => 'Yerba Mate Taragüi 1kg',
                'descripcion' => 'Yerba mate con palo, elaborada tradicional.',
                'stock' => 30,
                'stock_minimo' => 5,
                'precio_compra' => 600.00,
                'precio_venta' => 750.00,
            ],
            [
                'codigo' => '62718292',
                'nombre' => 'Aceite Cocinero 900ml',
                'descripcion' => 'Aceite mezcla para cocina.',
                'stock' => 20,
                'stock_minimo' => 8,
                'precio_compra' => 400.00,
                'precio_venta' => 520.00,
            ],
            [
                'codigo' => '11223344',
                'nombre' => 'Harina 0000 1kg',
                'descripcion' => 'Harina blanca todo uso.',
                'stock' => 70,
                'stock_minimo' => 15,
                'precio_compra' => 120.00,
                'precio_venta' => 180.00,
            ],
            [
                'codigo' => '22334455',
                'nombre' => 'Azúcar Ledesma 1kg',
                'descripcion' => 'Azúcar refinada blanca.',
                'stock' => 50,
                'stock_minimo' => 10,
                'precio_compra' => 140.00,
                'precio_venta' => 190.00,
            ],
            [
                'codigo' => '99887766',
                'nombre' => 'Leche La Serenísima 1L',
                'descripcion' => 'Leche entera larga vida.',
                'stock' => 25,
                'stock_minimo' => 5,
                'precio_compra' => 180.00,
                'precio_venta' => 240.00,
            ],
            [
                'codigo' => '33445566',
                'nombre' => 'Pan de molde Bimbo',
                'descripcion' => 'Pan lacteado en rebanadas.',
                'stock' => 18,
                'stock_minimo' => 4,
                'precio_compra' => 250.00,
                'precio_venta' => 320.00,
            ],
            [
                'codigo' => '44556677',
                'nombre' => 'Galletitas Oreo 117g',
                'descripcion' => 'Galletitas rellenas sabor chocolate.',
                'stock' => 35,
                'stock_minimo' => 5,
                'precio_compra' => 200.00,
                'precio_venta' => 280.00,
            ],
            [
                'codigo' => '55667788',
                'nombre' => 'Fideos Don Vicente 500g',
                'descripcion' => 'Fideos secos tipo spaghetti.',
                'stock' => 60,
                'stock_minimo' => 10,
                'precio_compra' => 130.00,
                'precio_venta' => 180.00,
            ],
            [
                'codigo' => '66778899',
                'nombre' => 'Arroz Gallo Oro 1kg',
                'descripcion' => 'Arroz blanco doble carolina.',
                'stock' => 70,
                'stock_minimo' => 15,
                'precio_compra' => 190.00,
                'precio_venta' => 260.00,
            ],
            [
                'codigo' => '77889900',
                'nombre' => 'Mermelada Dulcor Frutilla',
                'descripcion' => 'Mermelada de frutilla en frasco 454g.',
                'stock' => 25,
                'stock_minimo' => 6,
                'precio_compra' => 210.00,
                'precio_venta' => 280.00,
            ],
            [
                'codigo' => '88990011',
                'nombre' => 'Sal Fina Celusal 500g',
                'descripcion' => 'Sal de mesa refinada.',
                'stock' => 45,
                'stock_minimo' => 10,
                'precio_compra' => 70.00,
                'precio_venta' => 100.00,
            ],
            [
                'codigo' => '99001122',
                'nombre' => 'Mayonesa Natura 500g',
                'descripcion' => 'Mayonesa en pote mediano.',
                'stock' => 22,
                'stock_minimo' => 5,
                'precio_compra' => 260.00,
                'precio_venta' => 340.00,
            ],
        ];

        foreach ($productos as $producto) {
            Product::create($producto);
        }
    }
}
