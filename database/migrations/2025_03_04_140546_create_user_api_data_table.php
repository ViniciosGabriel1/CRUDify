<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserApiDataTable extends Migration
{
    public function up()
    {
        Schema::create('user_api_data', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_api_id');  // Relacionamento com a API
            $table->unsignedBigInteger('user_id');      // Relacionamento com o usuário
            $table->json('data');                       // Dados em formato JSON
            $table->timestamps();

            // Chaves estrangeiras
            $table->foreign('user_api_id')->references('id')->on('user_apis')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_api_data');
    }
}
