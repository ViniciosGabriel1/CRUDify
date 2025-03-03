<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;

    protected $fillable = ['message']; // Permite inserção em massa

    protected $connection = 'sqlite'; // Define SQLite como conexão do modelo




    function setSQLiteConnectionForUser($user_id, $user_name)
{
    $databasePath = database_path("$user_id$user_name/$user_name.sqlite"); // Caminho do banco de dados SQLite

    // Defina a configuração dinâmica para a conexão
    Config::set("database.connections.sqlite.database", $databasePath);
}
}
