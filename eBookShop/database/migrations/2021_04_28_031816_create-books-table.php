<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
             $table->string('title');
             $table->date('publication_date');
             $table->integer('author_id');
            $table->integer('publisher_id');
            $table->integer('categories_id');
            $table->integer('weight');
            $table->integer('number of pages');
            $table->string('formality');
            $table->float('size');
            $table->tinyInteger('foreign_book')->default(0);
            $table->decimal('price',8,3);
            $table->decimal('price_discount',8,3)->nullable();
            $table->decimal('percent_discount',8,3)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('books');
    }
}
