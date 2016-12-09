<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('sku')->unique()->index();
            $table->string('name')->index();
            $table->string('description')->nullable();
            $table->string('composition')->nullable();
            $table->string('care_label')->nullable();
            $table->string('measurement')->nullable();
            $table->boolean('active')->default(0);
            $table->text('images')->nullable();
            $table->enum('gender', ['woman', 'man'])->default('woman');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
