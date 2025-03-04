<?php

use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Models\Log as LogModel; // Renomeando para evitar conflito

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;



Route::get('api/create', [ApiController::class, 'create'])
    ->name('api.create');

Route::post('api/store', [ApiController::class, 'store'])
    ->name('api.store');

Route::get('dashboard', [ApiController::class, 'index'])
    ->name('dashboard');






















// Route::get('/test-sqlite-manual', function () {
//     // Conecta manualmente ao banco SQLite do usuário
//     $user_id = 12;
//     $user_name = 'test';
//     $connection = connectToSQLite($user_id, $user_name);
//     // dd($connection);
//     // Faz uma consulta à tabela 'logs'
//     $logs = $connection->table('logs')->get();

//     return $logs;
// });


// function connectToSQLite($user_id, $user_name)
// {
//     $databasePath = database_path("$user_id".$user_name."/test2.sqlite");

//     if (!file_exists($databasePath)) {
//         abort(404, "Banco de dados não encontrado.");
//     }

//     // Purge a conexão SQLite anterior
//     DB::purge('sqlite');

//     // Define a nova configuração da conexão SQLite
//     Config::set('database.connections.sqlite.database', $databasePath);

//     // Retorna a conexão configurada
//     return DB::connection('sqlite');
// }

// Rota para testar o log no banco SQLite do usuário 12Vinishow
// Route::get('/test-sqlite', function () {
//     // Cria um log no banco de dados SQLite do usuário 12Vinishow
//     createSQLiteDatabase(12, 'vinishow');

//     // Configura a conexão SQLite para o usuário
//     connectToSQLite(12, 'vinishow');

//     // Cria um log no banco de dados SQLite do usuário
//     LogModel::create(['message' => 'Teste de log na Vinishow121212']);

//     // Retorna todos os logs registrados
//     return LogModel::all();
// });



// use Illuminate\Support\Facades\File;

// function createSQLiteDatabase($user_id, $user_name)
// {
//     // Define o caminho para o banco de dados SQLite do usuário
//     $databasePath = database_path("$user_id"."$user_name/$user_name.sqlite");

//     // Define o caminho para as migrations específicas do usuário
//     $migrationsPath = database_path("migrations/$user_id"."$user_name");

//     // Verifique se o banco de dados SQLite já existe
//     if (!File::exists($databasePath)) {
//         // Crie o banco de dados SQLite se não existir
//         File::makeDirectory(database_path("$user_id"."$user_name"), 0755, true);
//         touch($databasePath); // Cria o arquivo SQLite
//     }

//     // Crie a pasta de migrations caso não exista
//     if (!File::exists($migrationsPath)) {
//         File::makeDirectory($migrationsPath, 0755, true); // Cria a pasta para migrations
//     }
// }



// function setSQLiteConnection($user_id, $user_name)
// {
//     $connectionName = 'sqlite_' . $user_id . '_' . $user_name;

//     // Configuração da conexão SQLite para o usuário
//     Config::set("database.connections.$connectionName", [
//         'driver' => 'sqlite',
//         'database' => database_path("$user_id"."$user_name/$user_name.sqlite"),
//         'prefix' => '',
//     ]);

//     // Defina a conexão ativa para o usuário
//     return $connectionName;
// }

// Route::get('/', function () {
//     connectToSQLite(12, 'vinishow');

//        // Recuperar todos os logs do banco SQLite
//        $logs = LogModel::latest()->get();

//        // Enviar os logs para a view Inertia
//        return Inertia::render('Welcome', [
//            'logs' => $logs,
//        ]);
// })->name('home');
Route::get('/', function () {

    // Enviar os logs para a view Inertia
    return Inertia::render(
        'Welcome'
    );
})->name('home');

// Route::get('dashboard', function () {
//     return Inertia::render('Dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');



require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
