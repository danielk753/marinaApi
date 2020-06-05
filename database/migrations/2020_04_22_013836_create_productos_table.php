<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->nullable(false);
            $table->bigInteger('codigo_producto')->nullable(false)->unique();
            $table->text('descripcion')->nullable(true);
            $table->string('marca')->nullable(true);
            $table->date('fecha_caducidad')->nullable(true);
            $table->string('ubicacion')->nullable(true);
            $table->string('unidad_medida')->nullable(true);
            $table->string('presentacion')->nullable(true);
            $table->integer('stock_minimo')->nullable(true);
            $table->integer('stock_maximo')->nullable(true);
            $table->integer('stock_actual')->nullable(true);
            $table->float('costo')->nullable(true);
            $table->float('iva')->nullable(true);
            $table->float('precio')->nullable(true);
            $table->float('utilidad_bruta')->nullable(true);
            $table->float('descuento')->nullable(true);
            $table->float('comision')->nullable(true);
            $table->text('imagen')->nullable(true);
            $table->boolean('visible')->default(true);
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
        Schema::dropIfExists('productos');
    }
}
