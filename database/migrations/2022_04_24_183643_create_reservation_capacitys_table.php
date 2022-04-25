<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservation_capacitys', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->integer('daily_capacity');
            $table->integer('table_capacity');

            $table->time('min_time');
            $table->time('max_time');

            $table->integer('reservation_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reservation_capacitys');
    }
};
