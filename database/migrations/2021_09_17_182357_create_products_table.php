<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->id();
            $table->unsignedBigInteger('store_id');
            $table->string('product_name')->nullable();
            $table->string('product_name_es')->nullable();
            $table->unsignedBigInteger('subcategory_id');
            $table->tinyInteger('product_language')->comment('1=>English,2=>Spanish,3=>both')->default(1);
            $table->decimal('price',10,2);
            $table->integer('discount')->nullable();
            $table->string('size')->nullable();
            $table->bigInteger('quantity');
            $table->longText('description')->nullable();
            $table->longText('description_es')->nullable();
            $table->string('manufacturer_name')->nullable();
            $table->string('manufacturer_name_es')->nullable();
            $table->string('sachet_capsule')->nullable();
            $table->longText('direction_to_use')->nullable();
            $table->longText('direction_to_use_es')->nullable();
            $table->longText('ingredients')->nullable();
            $table->longText('ingredients_es')->nullable();
            $table->longText('other_info')->nullable();
            $table->longText('other_info_es')->nullable();
            $table->boolean('product_status')->default(1)->comment('1=>show for sell 0=>not show');
            $table->timestamps();
            $table->foreign('subcategory_id')->references('id')->on('subcategories')->cascadeOnDelete();
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
