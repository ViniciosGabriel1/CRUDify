<?php


use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

function setSQLiteConnection($user_id, $user_name)
{
    $connectionName = 'sqlite_' . $user_id . '_' . $user_name;

    // Configuração da conexão SQLite para o usuário
    Config::set("database.connections.$connectionName", [
        'driver' => 'sqlite',
        'database' => database_path("$user_id_$user_name/$user_name.sqlite"),
        'prefix' => '',
    ]);
    
    // Defina a conexão ativa para o usuário
    return $connectionName;
}



function connectToSQLite($user_id, $user_name)
{
    $databasePath = database_path("$user_id".$user_name."/test2.sqlite");

    if (!file_exists($databasePath)) {
        abort(404, "Banco de dados não encontrado.");
    }

    // Purge a conexão SQLite anterior
    DB::purge('sqlite');

    // Define a nova configuração da conexão SQLite
    Config::set('database.connections.sqlite.database', $databasePath);

    // Retorna a conexão configurada
    return DB::connection('sqlite');
}
