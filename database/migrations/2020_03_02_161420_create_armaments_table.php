<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArmamentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('armaments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('spacecraft_id');
            $table->string('title');
            $table->integer('qty');

            $table->foreign('spacecraft_id')->references('id')->on('spacecrafts')->onDelete('cascade');
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
        Schema::dropIfExists('armaments');
    }
}
