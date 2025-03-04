<?php

namespace App\Services;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Inertia\Inertia;

class ApiService
{
    //
    public function connectToSQLite()
    {
        $show = auth()->user();

        $user_name = $show['name'];
        $user_id = $show['id'];


        $databasePath = database_path("$user_id" . $user_name . "/" . "$user_id" . "$user_name.sqlite");

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




    // function createSQLiteDatabase($request)
    // {

    //     $show = auth()->user();

    //     $user_name =$show['name'] ;
    //     $user_id = $show['id'];
    //     // Define o caminho para o banco de dados SQLite do usuário
    //     // $databasePath = database_path("$user_id"."$user_name/$user_name.sqlite");
    //     $databasePath = database_path("$user_id".$user_name."/"."$user_id"."$user_name.sqlite");


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

    public function createSQLiteDatabase($request)
    {
        $user = auth()->user();
        $user_id = $user->id;
        $user_name = $user->name;

        // Define o caminho do banco de dados SQLite do usuário
        $databasePath = database_path("{$user_id}{$user_name}/{$user_id}{$user_name}.sqlite");


        // Define o caminho para as migrations específicas do usuário
        $migrationsPath = database_path("migrations/{$user_id}{$user_name}");

        // Criar o banco de dados SQLite se não existir
        if (!File::exists($databasePath)) {
            File::makeDirectory(database_path("{$user_id}{$user_name}"), 0755, true);
            touch($databasePath); // Cria o arquivo SQLite
        }

        // Criar a pasta de migrations caso não exista
        if (!File::exists($migrationsPath)) {
            File::makeDirectory($migrationsPath, 0755, true);
        }

        // Criar Migration
        $apiName = $request->input('apiName');
        $columns = $request->input('columns');

        $this->createMigration($user_id, $user_name, $apiName, $columns);

        return response()->json([
            'message' => 'Banco de dados e migration criados com sucesso!',
            'databasePath' => $databasePath,
            'migrationsPath' => $migrationsPath
        ]);
    }


    private function createMigration($user_id, $user_name, $apiName, $columns)
    {
        // Define o caminho das migrations do usuário
        $migrationsPath = database_path("migrations/{$user_id}{$user_name}");

        // Nome do arquivo de migration
        $timestamp = now()->format('Y_m_d_His');
        $migrationFileName = "{$timestamp}_create_" . Str::snake($apiName) . "_table.php";
        $migrationFilePath = "$migrationsPath/$migrationFileName";

        // Gerar conteúdo da migration
        $migrationContent = $this->generateMigrationContent($apiName, $columns);

        // Criar o arquivo de migration
        File::put($migrationFilePath, $migrationContent);
    }


    private function generateMigrationContent($tableName, $columns)
    {
        $className = 'Create' . Str::studly($tableName) . 'Table';

        $columnsCode = "";
        foreach ($columns as $column) {
            $columnName = $column['name'];
            $columnType = $column['type'];

            $columnsCode .= "\$table->$columnType('$columnName');\n            ";
        }

        return "<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('$tableName', function (Blueprint \$table) {
            \$table->id();
            $columnsCode
            \$table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('$tableName');
    }
};
";
    }

    public function runUserMigrations($user_id, $user_name)
    {
        $databasePath = database_path("{$user_id}{$user_name}/{$user_id}{$user_name}.sqlite");

        // Configurar conexão temporária com o banco SQLite do usuário
        config(['database.connections.user_sqlite' => [
            'driver' => 'sqlite',
            'database' => $databasePath,
            'prefix' => '',
        ]]);

        // Executar as migrations da pasta específica do usuário
        \Artisan::call('migrate', [
            '--database' => 'user_sqlite',
            '--path' => "database/migrations/{$user_id}{$user_name}",
            '--force' => true
        ]);
    }
}
