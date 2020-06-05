<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketsventasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticketsventas', function (Blueprint $table) {
            $table->id();
            $table->double('subtotal');
            $table->double('iva');
            $table->double('total');
            $table->boolean('aplicado')->default(true)->nullable();
            $table->foreignId('cliente_id')->nullable();
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
        Schema::dropIfExists('ticketsventas');
    }
}
