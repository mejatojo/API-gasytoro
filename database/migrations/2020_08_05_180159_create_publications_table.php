<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePublicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('publications', function (Blueprint $table) {
            $table->id();
            $table->text('contenu');
            $table->boolean('resolu')->default(false);
            $table->unsignedBigInteger('idUser');
            $table->unsignedBigInteger('idDomaine');
            $table->foreign('idDomaine')->references('id')->on('domaines');
            $table->foreign('idUser')->references('id')->on('users');
            
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
        Schema::dropIfExists('publications');
    }
}
