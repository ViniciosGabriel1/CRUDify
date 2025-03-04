<?php

namespace App\Services;

use App\Models\UserApi;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Inertia\Inertia;

class ApiService
{
    //



    public function store_service($request)
    {
        // Chama a criação do banco de dados e das migrations
        $result = $this->createSQLiteDatabase($request);

        // Verifica se houve algum erro na criação do banco ou migrations
        if ($result['status'] === 'erro') {
            return response()->json([
                'status' => 'erro',
                'message' => $result['message'],
                'table' => $result['table'] ?? null
            ], 400);
        }

        $user = auth()->user();
        $id_user = $user->id;
        $user_name = $user->name;

        // Rodar migrations no banco SQLite do usuário
        $migrationResponse = $this->runUserMigrations($id_user, $user_name);
        // dd($migrationResponse);
        // Caso haja erro ao rodar as migrations
        if ($migrationResponse['status'] === 'erro') {
            return response()->json([
                'status' => 'erro',
                'message' => 'Ocorreu um erro ao rodar a migration.',
                'errorDetails' => $migrationResponse['error'] ?? 'Erro desconhecido.'
            ], 500);
        }


        // Caso não tenha erro, confirma a criação
        return response()->json([
            'status' => 'sucesso',
            'message' => 'Banco de dados e migration criados com sucesso!',
            'apiName' => $request->input('apiName'),
        ], 200);
    }

    public function connectToSQLite()
    {
        $show = auth()->user();

        $user_name = $show['name'];
        $id_user = $show['id'];


        $databasePath = database_path("$id_user" . $user_name . "/" . "$id_user" . "$user_name.sqlite");

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


    public function createSQLiteDatabase($request)
    {

        $user = auth()->user();
        $id_user = $user->id;
        $user_name = $user->name;
        $apiName = $request->input('apiName');
        $columns = $request->input('columns');

        $databasePath = database_path("{$id_user}{$user_name}/{$id_user}{$user_name}.sqlite");


        // Define o caminho para as migrations específicas do usuário
        $migrationsPath = database_path("migrations/{$id_user}{$user_name}");

        // Verifica se a tabela já existe
        if ($this->checkTableExists($apiName)) {

            return [
                'status' => 'erro',
                'message' => "A tabela '{$apiName}' já existe.",
                'table' => $apiName,
                400
            ];
        }

        // Criar o banco de dados SQLite se não existir
        if (!File::exists($databasePath)) {
            File::makeDirectory(database_path("{$id_user}{$user_name}"), 0755, true);
            touch($databasePath); // Cria o arquivo SQLite
        }

        // Tenta criar a migração
        $migrationStatus = $this->createMigration($id_user, $user_name, $apiName, $columns);

        if ($migrationStatus['status'] === 'erro') {
            return response()->json([
                'message' => "Erro ao criar a tabela  '{$apiName}'.",
                'table' => $apiName
            ], 400);
        }

        $existingApi = UserApi::where('id_user', $id_user)
            ->where('api_name', $apiName)
            ->first();

        if ($existingApi) {

            return response()->json([
                'status' => 'erro',
                'message' => "A API '{$apiName}' já está registrada para o usuário."
            ], 400);
        }

        // Salva os dados no banco de dados
        UserApi::create([
            'id_user' => $id_user,
            'api_name' => $apiName,
            'database_path' => $databasePath,
            'migrations_path' => $migrationsPath,
        ]);


        return [
            'status' => 'sucesso',
            'message' => 'Banco de dados e migration criados com sucesso!',
            'migrationFile' => $migrationStatus['migrationFile'],
            'migrationsPath' => $migrationStatus['migrationFilePath'],
            'apiName' => $request->input('apiName'),
            200
        ];
    }

    private function createMigration($id_user, $user_name, $apiName, $columns)
    {
        // Define o caminho das migrations do usuário
        $migrationsPath = database_path("migrations/{$id_user}{$user_name}");

        $databasePath = database_path("{$id_user}{$user_name}/{$id_user}{$user_name}.sqlite");


        // Criar a pasta de migrations caso não exista
        if (!File::exists($migrationsPath)) {
            File::makeDirectory($migrationsPath, 0755, true);
        }

        config(['database.connections.user_sqlite' => [
            'driver' => 'sqlite',
            'database' => $databasePath,
            'prefix' => '',
        ]]);

        if (Schema::connection('user_sqlite')->hasTable($apiName)) {
            return [
                'status' => 'erro',
                'message' => "A tabela '{$apiName}' já existe.",
                'table' => $apiName,
                400
            ];
        }




        // Nome do arquivo de migration
        $timestamp = now()->format('Y_m_d_His');
        $migrationFileName = "{$timestamp}_create_" . Str::snake($apiName) . "_table.php";
        $migrationFilePath = "$migrationsPath/$migrationFileName";

        // Gerar conteúdo da migration
        $migrationContent = $this->generateMigrationContent($apiName, $columns);

        // Criar o arquivo de migration
        File::put($migrationFilePath, $migrationContent);


        return [
            'status' => 'sucesso',
            'message' => 'Migration criada com sucesso!',
            'migrationFile' => $migrationFileName,
            'migrationFilePath' => $migrationFilePath
        ];
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

    // public function runUserMigrations($id_user, $user_name)
    // {
    //     $databasePath = database_path("{$id_user}{$user_name}/{$id_user}{$user_name}.sqlite");

    //     // Configurar conexão temporária com o banco SQLite do usuário
    //     config(['database.connections.user_sqlite' => [
    //         'driver' => 'sqlite',
    //         'database' => $databasePath,
    //         'prefix' => '',
    //     ]]);

    //     // Executar as migrations da pasta específica do usuário
    //     \Artisan::call('migrate', [
    //         '--database' => 'user_sqlite',
    //         '--path' => "database/migrations/{$id_user}{$user_name}",
    //         '--force' => true
    //     ]);


    // }

    public function runUserMigrations($id_user, $user_name)
    {
        $databasePath = database_path("{$id_user}{$user_name}/{$id_user}{$user_name}.sqlite");

        // Verificar se o banco de dados SQLite existe
        if (!file_exists($databasePath)) {
            return [
                'status' => 'erro',
                'message' => 'O banco de dados SQLite não foi encontrado.',
            ];
        }


        // Configurar a conexão temporária com o banco SQLite do usuário
        config(['database.connections.user_sqlite' => [
            'driver' => 'sqlite',
            'database' => $databasePath,
            'prefix' => '',
        ]]);



        // Tentar executar as migrations da pasta específica do usuário
        try {
            \Artisan::call('migrate', [
                '--database' => 'user_sqlite',
                '--path' => "database/migrations/{$id_user}{$user_name}",
                '--force' => true
            ]);


            return [
                'status' => 'sucesso',
                'message' => 'Migrations executadas com sucesso.',
            ];
        } catch (\Exception $e) {
            // Captura qualquer exceção e retorna a mensagem de erro
            return [
                'status' => 'erro',
                'message' => 'Ocorreu um erro ao rodar as migrations: ' . $e->getMessage(),
            ];
        }
    }



    // Verifica se a tabela existe
    protected function checkTableExists($apiName)
    {
        // Obtém o caminho do banco de dados SQLite do usuário
        $user = auth()->user();
        $id_user = $user->id;
        $user_name = $user->name;
        $databasePath = database_path("{$id_user}{$user_name}/{$id_user}{$user_name}.sqlite");

        // Verifica se o arquivo SQLite existe
        if (!file_exists($databasePath)) {
            return false; // Retorna false se o banco de dados não existir
        }

        // Configura a conexão dinamicamente para o banco de dados SQLite do usuário
        config(['database.connections.user_sqlite' => [
            'driver' => 'sqlite',
            'database' => $databasePath,
            'prefix' => '',
        ]]);

        // Verifica se a tabela existe no banco de dados SQLite do usuário
        return Schema::connection('user_sqlite')->hasTable($apiName);
    }


    // Gera resposta de erro padronizada
    protected function generateErrorResponse($message, $statusCode, $table)
    {
        return [
            'status' => 'erro',
            'message' => $message,
            'table' => $table,
            'status_code' => $statusCode
        ];
    }



    // function createSQLiteDatabase($request)
    // {

    //     $show = auth()->user();

    //     $user_name =$show['name'] ;
    //     $id_user = $show['id'];
    //     // Define o caminho para o banco de dados SQLite do usuário
    //     // $databasePath = database_path("$id_user"."$user_name/$user_name.sqlite");
    //     $databasePath = database_path("$id_user".$user_name."/"."$id_user"."$user_name.sqlite");


    //     // Define o caminho para as migrations específicas do usuário
    //     $migrationsPath = database_path("migrations/$id_user"."$user_name");

    //     // Verifique se o banco de dados SQLite já existe
    //     if (!File::exists($databasePath)) {
    //         // Crie o banco de dados SQLite se não existir
    //         File::makeDirectory(database_path("$id_user"."$user_name"), 0755, true);
    //         touch($databasePath); // Cria o arquivo SQLite
    //     }

    //     // Crie a pasta de migrations caso não exista
    //     if (!File::exists($migrationsPath)) {
    //         File::makeDirectory($migrationsPath, 0755, true); // Cria a pasta para migrations
    //     }
    // }
}
