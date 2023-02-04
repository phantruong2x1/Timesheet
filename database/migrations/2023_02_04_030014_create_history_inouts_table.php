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
        Schema::create('history_inouts', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('record_id')->nullable();
            $table->bigInteger('time')->nullable();
            $table->string('staff_id',255)->nullable();
            $table->integer('record_type')->nullable();
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
        Schema::dropIfExists('history_inouts');
    }
};
