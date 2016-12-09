<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Models\ProductVariation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use TCG\Voyager\Http\Controllers\VoyagerBreadController;
use TCG\Voyager\Models\DataType;

class ProductController extends VoyagerBreadController
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // GET THE DataType based on the slug
        $variations = ProductVariation::with('product')->orderBy('product_id', 'desc')->orderBy('updated_at',
            'desc')->get();

        $dataType = DataType::where('slug', '=', 'products')->first();

        return view('voyager::products.browse', compact('variations', 'dataType'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $slug = $request->segment(2);
        $dataType = DataType::where('slug', '=', $slug)->first();

        DB::beginTransaction();

        try {
            $data = new $dataType->model_name;
            $data = $this->insertUpdateData($request, $slug, $dataType->addRows, $data);

            $this->validate($request, [
                'variations' => 'required|min:1',
            ], [
                'variations.required' => 'Product pricing required.',
                'variations.min'      => 'Product pricing minimum :min item.',
            ]);

            // Save variations
            foreach ($request->input('variations') as $item) {
                if ($item['size']) {
                    $variation = new ProductVariation($item);
                    $variation->available = $item['quantity'] - $variation->reserved;
                    $variation->sku = strtoupper(str_slug($data->sku.'-'.$item['size'], '-'));
                    $data->variations()->save($variation);
                }
            }

            $validation['variations'] = $data->variations()->get();

            $validator = Validator::make($validation, [
                'variations' => 'required|min:1',
            ], [
                'variations.required' => 'Product pricing required.',
                'variations.min'      => 'Product pricing minimum :min item.',
            ]);

            if ($validator->fails()) {
                return Redirect::back()->withErrors($validator)->withInput();
            }

            $data->productCategories()->sync($request->input('product_categories', []));

            DB::commit();

        } catch (Exception $e) {
            DB::rollBack();
        }

        return redirect(route('voyager.'.$dataType->slug.'.index'))->with([
            'message'    => 'Successfully Added New '.$dataType->display_name_singular,
            'alert-type' => 'success',
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $slug = $request->segment(2);
        $dataType = DataType::where('slug', '=', $slug)->first();

        $product = Product::with('variations', 'productCategories')->find($id);

        return view('voyager::products.edit-add', compact('dataType', 'product'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $slug = $request->segment(2);
        $dataType = DataType::where('slug', '=', $slug)->first();

        DB::beginTransaction();

        try {
            $data = call_user_func([$dataType->model_name, 'find'], $id);
            $this->insertUpdateData($request, $slug, $dataType->editRows, $data);

            // Delete non-exist variations
            foreach ($data->variations as $variation) {
                $exist = false;
                foreach ($request->input('variations') as $item) {
                    if (isset($item['id']) && $variation->id == $item['id']) {
                        $exist = true;
                    }
                }
                if (!$exist) {
                    $variation->delete();
                }
            }

            // Save variations
            foreach ($request->input('variations') as $item) {
                if (isset($item['id'])) {
                    if (!$item['size']) {
                        return Redirect::back()->withErrors(['size' => 'Product size required.'])->withInput();
                    }
                    $variation = ProductVariation::find($item['id']);
                    $variation->available = $item['quantity'] - $variation->reserved;
                    $variation->sku = strtoupper(str_slug($data->sku.'-'.$item['size'], '-'));
                    $variation->update($item);
                } else {
                    if ($item['size']) {
                        $variation = new ProductVariation($item);
                        $variation->available = $item['quantity'] - $variation->reserved;
                        $variation->sku = strtoupper(str_slug($data->sku.'-'.$item['size'], '-'));
                        $data->variations()->save($variation);
                    }
                }
            }

            $validation['variations'] = $data->variations()->get();

            $validator = Validator::make($validation, [
                'variations' => 'required|min:1',
            ], [
                'variations.required' => 'Product pricing required.',
                'variations.min'      => 'Product pricing minimum :min item.',
            ]);

            if ($validator->fails()) {
                return Redirect::back()->withErrors($validator)->withInput();
            }

            $data->productCategories()->sync($request->input('product_categories', []));

            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
        }

        return redirect(route('voyager.'.$dataType->slug.'.index'))->with([
            'message'    => 'Successfully Updated '.$dataType->display_name_singular,
            'alert-type' => 'success',
        ]);
    }
}
