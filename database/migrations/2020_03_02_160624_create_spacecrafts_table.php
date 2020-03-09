<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Spacecraft;

class CreateSpacecraftsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('spacecrafts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->unique();
            $table->string('class');
            $table->integer('crew');
            $table->string('image');
            $table->double('value');
            $table->tinyInteger('status'); //in theory, this should be enum, I am just using tiny integer here because there articles about the downsides of using Enum in Laravel
            $table->unsignedBigInteger('fleet_id');
            $table->foreign('fleet_id')->references('id')->on('fleets')->onDelete('cascade');
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
        Schema::dropIfExists('spacecrafts');
    }
}
