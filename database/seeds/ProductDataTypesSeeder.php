<?php

use Illuminate\Database\Seeder;

class ProductDataTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dataType = DataType::firstOrNew([
            'slug'                  => 'products',
        ]);
        if (!$dataType->exists) {
            $dataType->fill([
                'name'                  => 'products',
                'display_name_singular' => 'Product',
                'display_name_plural'   => 'Products',
                'icon'                  => 'voyager-shop',
                'model_name'            => 'App\Models\Product',
                'generate_permissions'  => 1,
                'description'           => '',
            ])->save();
        }
    }
}
