<?php

use Illuminate\Database\Seeder;
use TCG\Voyager\Models\DataType;
use TCG\Voyager\Models\Menu;
use TCG\Voyager\Models\Permission;

class ProductCategoriesDataTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dataType = DataType::firstOrNew([
            'slug' => 'product-categories',
        ]);
        if (!$dataType->exists) {
            $dataType->fill([
                'name'                  => 'product_categories',
                'display_name_singular' => 'Product Category',
                'display_name_plural'   => 'Product Categories',
                'icon'                  => 'voyager-list',
                'model_name'            => 'App\Models\ProductCategory',
                'generate_permissions'  => 1,
                'description'           => '',
            ])->save();
        }

        Permission::generateFor('product_categories');

        $menu = Menu::where('name', 'admin')->firstOrFail();

        $menuItem = MenuItem::firstOrNew([
            'menu_id'    => $menu->id,
            'title'      => 'Product Categories',
            'url'        => route('voyager.product-categories.index', [], false),
        ]);
        if (!$menuItem->exists) {
            $menuItem->fill([
                'target'     => '_self',
                'icon_class' => 'voyager-shop',
                'color'      => null,
                'parent_id'  => null,
                'order'      => 12,
            ])->save();
        }
    }
}
