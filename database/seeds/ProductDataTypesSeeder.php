<?php

use Illuminate\Database\Seeder;
use TCG\Voyager\Models\DataRow;
use TCG\Voyager\Models\DataType;
use TCG\Voyager\Models\Menu;
use TCG\Voyager\Models\MenuItem;
use TCG\Voyager\Models\Permission;

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
            'slug' => 'products',
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

        Permission::generateFor('products');

        $menu = Menu::where('name', 'admin')->firstOrFail();

        $menuItem = MenuItem::firstOrNew([
            'menu_id'    => $menu->id,
            'title'      => 'Products',
            'url'        => route('voyager.products.index', [], false),
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

        $dataRow = DataRow::firstOrNew([
            'data_type_id' => $dataType->id,
            'field'        => 'id',
        ]);
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'PRI',
                'display_name' => 'ID',
                'required'     => 1,
                'browse'       => 0,
                'read'         => 0,
                'edit'         => 0,
                'add'          => 0,
                'delete'       => 0,
                'details'      => '',
            ])->save();
        }

        $dataRow = DataRow::firstOrNew([
            'data_type_id' => $dataType->id,
            'field'        => 'sku',
        ]);
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'text',
                'display_name' => 'SKU',
                'required'     => 1,
                'browse'       => 1,
                'read'         => 1,
                'edit'         => 1,
                'add'          => 1,
                'delete'       => 1,
                'details'      => '{
"validation": {
"rule": "required"
}
}',
            ])->save();
        }

        $dataRow = DataRow::firstOrNew([
            'data_type_id' => $dataType->id,
            'field'        => 'name',
        ]);
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'text',
                'display_name' => 'Name',
                'required'     => 1,
                'browse'       => 1,
                'read'         => 1,
                'edit'         => 1,
                'add'          => 1,
                'delete'       => 1,
                'details'      => '{
"validation": {
"rule": "required"
}
}',
            ])->save();
        }

        $dataRow = DataRow::firstOrNew([
            'data_type_id' => $dataType->id,
            'field'        => 'description',
        ]);
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'text',
                'display_name' => 'Description',
                'required'     => 0,
                'browse'       => 1,
                'read'         => 1,
                'edit'         => 1,
                'add'          => 1,
                'delete'       => 1,
                'details'      => '',
            ])->save();
        }

        $dataRow = DataRow::firstOrNew([
            'data_type_id' => $dataType->id,
            'field'        => 'composition',
        ]);
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'text',
                'display_name' => 'Composition',
                'required'     => 0,
                'browse'       => 1,
                'read'         => 1,
                'edit'         => 1,
                'add'          => 1,
                'delete'       => 1,
                'details'      => '',
            ])->save();
        }

        $dataRow = DataRow::firstOrNew([
            'data_type_id' => $dataType->id,
            'field'        => 'care_label',
        ]);
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'text',
                'display_name' => 'Care Label',
                'required'     => 0,
                'browse'       => 1,
                'read'         => 1,
                'edit'         => 1,
                'add'          => 1,
                'delete'       => 1,
                'details'      => '',
            ])->save();
        }

        $dataRow = DataRow::firstOrNew([
            'data_type_id' => $dataType->id,
            'field'        => 'measurement',
        ]);
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'text',
                'display_name' => 'Measurement',
                'required'     => 0,
                'browse'       => 1,
                'read'         => 1,
                'edit'         => 1,
                'add'          => 1,
                'delete'       => 1,
                'details'      => '',
            ])->save();
        }

        $dataRow = DataRow::firstOrNew([
            'data_type_id' => $dataType->id,
            'field'        => 'active',
        ]);
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'checkbox',
                'display_name' => 'Active',
                'required'     => 1,
                'browse'       => 1,
                'read'         => 1,
                'edit'         => 1,
                'add'          => 1,
                'delete'       => 1,
                'details'      => '',
            ])->save();
        }

        $dataRow = DataRow::firstOrNew([
            'data_type_id' => $dataType->id,
            'field'        => 'images',
        ]);
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'text',
                'display_name' => 'Images',
                'required'     => 0,
                'browse'       => 1,
                'read'         => 1,
                'edit'         => 1,
                'add'          => 1,
                'delete'       => 1,
                'details'      => '{
"validation": {
"rule": "required"
}
}',
            ])->save();
        }

        $dataRow = DataRow::firstOrNew([
            'data_type_id' => $dataType->id,
            'field'        => 'created_at',
        ]);
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'timestamp',
                'display_name' => 'Created At',
                'required'     => 0,
                'browse'       => 1,
                'read'         => 1,
                'edit'         => 0,
                'add'          => 0,
                'delete'       => 0,
                'details'      => '',
            ])->save();
        }

        $dataRow = DataRow::firstOrNew([
            'data_type_id' => $dataType->id,
            'field'        => 'updated_at',
        ]);
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'timestamp',
                'display_name' => 'Updated At',
                'required'     => 0,
                'browse'       => 0,
                'read'         => 0,
                'edit'         => 0,
                'add'          => 0,
                'delete'       => 0,
                'details'      => '',
            ])->save();
        }

        $dataRow = DataRow::firstOrNew([
            'data_type_id' => $dataType->id,
            'field'        => 'deleted_at',
        ]);
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'timestamp',
                'display_name' => 'Deleted At',
                'required'     => 0,
                'browse'       => 0,
                'read'         => 0,
                'edit'         => 0,
                'add'          => 0,
                'delete'       => 0,
                'details'      => '',
            ])->save();
        }
    }
}
