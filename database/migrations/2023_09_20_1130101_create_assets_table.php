<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->foreignId('office_id')->nullable()->constrained('offices')->cascadeOnDelete()->nullable();
            $table->foreignId('section_id')->nullable()->constrained('sections')->cascadeOnDelete()->nullable();
            $table->foreignId('staff_id')->nullable()->constrained('staff')->cascadeOnDelete()->nullable();
            $table->string('condition')->nullable();
            $table->string('category')->nullable();
            $table->string('subcategory')->nullable();
            $table->string('asset_tag')->nullable();
            $table->timestamp('purchase_date')->nullable();
            $table->string('purchasing_price')->nullable();
            $table->year('manufactured_year')->nullable();
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
        Schema::dropIfExists('assets');
    }
}
