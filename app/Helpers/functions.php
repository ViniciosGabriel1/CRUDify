<?php


use Illuminate\Support\Facades\Config;

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