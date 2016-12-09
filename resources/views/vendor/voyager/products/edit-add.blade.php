@extends('voyager::master')

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/bootstrap-datepicker.min.css') }}"/>
    <link rel="stylesheet" href="{{ config('voyager.assets_path') }}/js/dropzone/dropzone.css"/>
@stop

@section('page_header')
    <h1 class="page-title">
        <i class="{{ $dataType->icon }}"></i> @if(isset($product->id)){{ 'Edit' }}@else{{ 'New' }}@endif {{ $dataType->display_name_singular }}
    </h1>
@stop

@section('content')
    <div class="page-content container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="panel panel-bordered">

                    <div class="panel-heading">
                        <h3 class="panel-title">@if(isset($product->id)){{ 'Edit' }}@else{{ 'Add New' }}@endif {{ $dataType->display_name_singular }}</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form role="form"
                          action="@if(isset($product->id)){{ route('voyager.'.$dataType->slug.'.update', $product->id) }}@else{{ route('voyager.'.$dataType->slug.'.store') }}@endif"
                          method="POST" enctype="multipart/form-data">

                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active"><a href="#info" aria-controls="info" role="tab"
                                                                      data-toggle="tab">Product Info</a></li>
                            <li role="presentation"><a href="#details" aria-controls="details" role="tab"
                                                       data-toggle="tab">Details</a></li>
                            <li role="presentation"><a href="#pricing" aria-controls="pricing" role="tab"
                                                       data-toggle="tab">Pricing & Sizes</a></li>
                            <li role="presentation"><a href="#images" aria-controls="images" role="tab"
                                                       data-toggle="tab">Images</a></li>
                        </ul>

                        <hr>

                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                    <!-- Tab panes -->
                    <div class="tab-content">
                        <!-- Product Info Tab -->
                        <div role="tabpanel" class="tab-pane active" id="info">

                            <div class="form-group">
                                <label for="gender">Product Gender</label>
                                <?php $selected_value = (isset($product->gender) && ! empty(old('gender',
                                                $product->gender))) ? old('gender',
                                        $product->gender) : old('gender'); ?>
                                <select class="form-control" name="gender">
                                    <option value="woman" @if($selected_value == 'woman'){{ 'selected="selected"' }}@endif>
                                        Woman
                                    </option>
                                    <option value="man" @if($selected_value == 'man'){{ 'selected="selected"' }}@endif>
                                        Man
                                    </option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="name">Product Name</label>
                                <input type="text" class="form-control" id="name" name="name"
                                       placeholder="Product Name"
                                       value="@if(isset($product->name)){{ old('name', $product->name) }}@else{{old('name')}}@endif">
                            </div>

                            <div class="form-group">
                                <label for="sku">SKU</label>
                                <input type="text" class="form-control" id="sku" name="sku" placeholder="SKU"
                                       value="@if(isset($product->sku)){{ old('sku', $product->sku) }}@else{{old('sku')}}@endif">
                            </div>

                            <div class="form-group">
                                <label for="active">Active</label><br>
                                <?php $checked = (isset($product->active) && old('active',
                                                $product->active)) ? true : old('active'); ?>
                                <input type="checkbox" name="active" class="toggleswitch"
                                       @if($checked) checked @endif>
                            </div>

                            <div class="form-group">
                                <label for="category_product">Categories</label><br>
                                <ul class="checkbox product-categories">
                                    <?php $product_categories = (isset($product->productCategories)) ? old('product_categories', $product->productCategories->pluck('id')->toArray()) : old('product_categories', []); ?>
                                    @foreach(\App\Models\ProductCategory::where('parent_id', 0)->get() as $parent)
                                        <li class="parent"><input type="checkbox" name="product_categories[]" id="category_product_{{$parent->id}}" @if(in_array($parent->id, $product_categories)) checked @endif value="{{$parent->id}}"> <label for="category_product_{{$parent->id}}">{{$parent->display_name}}</label>
                                            <ul class="child">
                                                @foreach(\App\Models\ProductCategory::where('parent_id', $parent->id)->get() as $child)
                                                    <li><input type="checkbox" name="product_categories[]" @if(in_array($child->id, $product_categories)) checked @endif id="category_product_{{$child->id}}" value="{{$child->id}}"> <label for="category_product_{{$child->id}}">{{$child->display_name}}</label></li>
                                                @endforeach
                                            </ul>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                        </div>

                        <!-- Details Tab -->
                        <div role="tabpanel" class="tab-pane" id="details">

                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea class="form-control" name="description" id="description"
                                          rows="6">@if(isset($product->description)){{ $product->description }}@else{{old('description')}}@endif</textarea>
                            </div>

                            <div class="form-group">
                                <label for="composition">Composition</label>
                                <textarea class="form-control" name="composition" id="composition"
                                          rows="6">@if(isset($product->composition)){{ $product->composition }}@else{{old('composition')}}@endif</textarea>
                            </div>

                            <div class="form-group">
                                <label for="care_label">Care Label</label>
                                <textarea class="form-control" name="care_label" id="care_label"
                                          rows="6">@if(isset($product->care_label)){{ $product->care_label }}@else{{old('care_label')}}@endif</textarea>
                            </div>

                            <div class="form-group">
                                <label for="measurement">Measurement</label>
                                <textarea class="form-control" name="measurement" id="measurement"
                                          rows="6">@if(isset($product->measurement)){{ $product->measurement }}@else{{old('measurement')}}@endif</textarea>
                            </div>

                        </div>

                        <!-- Pricing Tab -->
                        <div role="tabpanel" class="tab-pane product-variations" id="pricing">

                            <table class="table table-bordered">
                                <tr>
                                    <th>Size</th>
                                    <th>Quantity</th>
                                    <th>Cost of Good</th>
                                    <th>Price</th>
                                    <th>Sale Price</th>
                                    <th>Sale Date</th>
                                    <th></th>
                                </tr>

                                <tr v-for="(index, variation) in variations">
                                    <td>
                                        <input type="hidden" class="form-control" v-if="variation.id" :value="variation.id"
                                               name="variations[@{{ index }}][id]">
                                        <input type="text" class="form-control" :value="variation.size"
                                               name="variations[@{{ index }}][size]" :disabled="variation.">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" :value="variation.quantity"
                                               name="variations[@{{ index }}][quantity]">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" :value="variation.cost_of_good"
                                               name="variations[@{{ index }}][cost_of_good]">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" :value="variation.price"
                                               name="variations[@{{ index }}][price]">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" :value="variation.sale_price"
                                               name="variations[@{{ index }}][sale_price]">
                                    </td>
                                    <td>
                                        <div class="input-daterange input-group">
                                            <input type="text" class="input-sm form-control sale-start"
                                                   :value="variation.sale_start"
                                                   name="variations[@{{ index }}][sale_start]"/>
                                            <span class="input-group-addon">to</span>
                                            <input type="text" class="input-sm form-control sale-end"
                                                   :value="variation.sale_end"
                                                   name="variations[@{{ index }}][sale_end]"/>
                                        </div>
                                    </td>
                                    <td>
                                        <button v-if="index !== 0" class="btn-xs btn btn-danger" type="button"
                                                v-on:click="removeVariation(index)"><i class="voyager-x"></i>
                                        </button>
                                    </td>
                                </tr>
                            </table>

                            <button class="btn btn-default" type="button" v-on:click="addVariation">+ Add
                                Product Variation
                            </button>
                        </div>

                        <!-- Images Tab -->
                        <div role="tabpanel" class="tab-pane" id="images">
                            <button class="btn btn-primary" type="button" id="upload"><i class="voyager-upload"></i>
                                Upload Image
                            </button>
                            <div id="uploadPreview" style="display:none;"></div>

                            <div id="uploadProgress" class="progress active progress-striped" style="display:none;">
                                <div class="progress-bar progress-bar-success" style="width: 0%"></div>
                            </div>
                            <div class="product-images">
                                <div class="row" id="sort-image">
                                    <div class="col-xs-6 col-md-3 col-lg-2 draggable" v-for="(index, path) in files">
                                        <div class="product-image">
                                            <img :src="path" class="img-responsive">
                                            <input type="hidden" name="images[]" :value="path">
                                            <div class="row">
                                                <div class="col-xs-6"><span v-if="index == 0">Main Image</span></div>
                                                <div class="col-xs-6 text-right"><button type="button" v-on:click="remove(index)" class="btn btn-default"><i class="voyager-trash"></i> Delete</button></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div><!-- panel-body -->


                <!-- PUT Method if we are editing -->
                @if(isset($product->id))
                    <input type="hidden" name="_method" value="PUT">
            @endif

            <!-- CSRF TOKEN -->
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <br>
                <div class="panel-footer">
                    <button type="submit" class="btn btn-success">Save Product</button>
                </div>
                </form>

                <iframe id="form_target" name="form_target" style="display:none"></iframe>
                <form id="my_form" action="{{ route('voyager.upload') }}" target="form_target" method="post"
                      enctype="multipart/form-data" style="width:0px;height:0;overflow:hidden">
                    <input name="image" id="upload_file" type="file" onchange="$('#my_form').submit();this.value='';">
                    <input type="hidden" name="type_slug" id="type_slug" value="{{ $dataType->slug }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                </form>

            </div>
        </div>
    </div>
    </div>
@stop

@section('javascript')
    <script src="{{ config('voyager.assets_path') }}/lib/js/tinymce/tinymce.min.js"></script>
    <script src="{{ config('voyager.assets_path') }}/js/voyager_tinymce.js"></script>
    <script src="{{ asset('assets/admin/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/sortable.min.js') }}"></script>
    <script src="{{ config('voyager.assets_path') }}/js/dropzone/dropzone.js"></script>
    <script>
        $('document').ready(function () {
            $('.toggleswitch').bootstrapToggle();

            var initDatePicker = function () {
                $('.input-daterange').datepicker({
                    format: "dd M yyyy",
                    autoclose: true,
                    todayHighlight: true,
                    daysOfWeekHighlighted: "0",
                });
            };

            $('.sale-start').on("changeDate", function () {
                $('.sale-end').focus();
            });

            var product_variations = [];

            @if(isset($product->variations))
                  product_variations = <?php echo json_encode(old('variations', $product->variations->toArray())); ?>;
            @elseif(old('variations'))
                    product_variations = <?php echo json_encode(old('variations')); ?>;
            @endif

            var variations = new Vue({
                el: '.product-variations',
                data: {
                    variations: product_variations
                },
                methods: {
                    addVariation: function () {
                        this.variations.push({});
                    },
                    removeVariation: function (index) {
                        if (this.variations.length > 1) this.variations.splice(index, 1);
                    }
                },
                ready: function () {
                    initDatePicker();
                },
                watch: {
                    'variations': function () {
                        initDatePicker();
                    }
                }
            });


            var product_images = [];

            @if(isset($product->images))
                product_images = <?php echo json_encode(old('images', $product->images)) ?>;
            @elseif(old('images'))
                product_images = <?php echo json_encode(old('images')); ?>;
            @endif

            var manager = new Vue({
                el: '.product-images',
                data: {
                    files: product_images
                },
                ready: function() {
                    var vm = this;
                    var list = document.getElementById("sort-image");
                    Sortable.create(list, {
                        draggable: '.draggable',
                        onUpdate: function(evt) {
                            vm.reorder(evt.oldIndex, evt.newIndex);
                        }
                    });
                },
                methods: {
                    reorder(oldIndex, newIndex) {
                        // move the item in the underlying array
                        this.files.splice(newIndex, 0, this.files.splice(oldIndex, 1)[0]);
                    },
                    remove: function(index) {
                        this.files.splice(index, 1);
                    }
                }
            });

            CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            var imageWidth = 762, imageHeight = 1100;
            $("#upload").dropzone({
                url: "{{ route('voyager.media.upload') }}",
                previewsContainer: "#uploadPreview",
                totaluploadprogress: function (uploadProgress, totalBytes, totalBytesSent) {
                    $('#uploadProgress .progress-bar').css('width', uploadProgress + '%');
                    if (uploadProgress == 100) {
                        $('#uploadProgress').delay(1500).slideUp(function () {
                            $('#uploadProgress .progress-bar').css('width', '0%');
                        });
                    }
                },
                init: function () {
                    this.on("thumbnail", function (file) {
                        if (file.width !== imageWidth || file.height !== imageHeight) {
                            file.rejectDimensions()
                        }
                        else {
                            file.acceptDimensions();
                        }
                    });
                },
                accept: function (file, done) {
                    file.acceptDimensions = done;
                    file.rejectDimensions = function () {
                        done("Image size must: " + imageWidth + "x" + imageHeight + "px");
                    };
                },
                acceptedFiles: ".jpeg,.jpg,.png,.gif",
                processing: function () {
                    $('#uploadProgress').fadeIn();
                },
                sending: function (file, xhr, formData) {
                    formData.append("_token", CSRF_TOKEN);
                    formData.append("upload_path", 'public/products');
                },
                success: function (e, res) {
                    if (res.success) {
                        manager.files.push('/storage/' + res.path);
                        toastr.success(res.message, "Sweet Success!");
                    } else {
                        toastr.error(res.message, "Whoopsie!");
                    }
                },
                error: function (e, res, xhr) {
                    toastr.error(res, "Whoopsie");
                },
                queuecomplete: function () {
                    //getFiles(manager.folders);
                }
            });
        });
    </script>
    <script type="text/javascript">
        $(function() {
            // Javascript to enable link to tab
            var hash = document.location.hash;
            if (hash) {
                $('.nav-tabs a[href='+hash+']').tab('show');
            }

            // Change hash for page-reload
            $('a[data-toggle="tab"]').on('show.bs.tab', function (e) {
                window.location.hash = e.target.hash;
            });

            /*var hasChanges = false;
            $(document).on('change', 'input, textarea, select', function() {
                hasChanges = true;
            });

            $(window).bind('beforeunload', function(){
                if(hasChanges)
                {
                    return 'Are you sure you want to leave?';
                }
            });*/
        });
    </script>
@stop