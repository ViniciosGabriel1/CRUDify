<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_user_api_columns_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserApiColumnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_api_columns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_api_id')->constrained('user_apis')->onDelete('cascade'); // Referência para a tabela 'user_apis'
            $table->string('name');
            $table->string('type'); // Tipo da coluna (string, integer, text, etc.)
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
        Schema::dropIfExists('user_api_columns');
    }
}
