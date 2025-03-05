<?php

namespace App\Services;

use App\Models\UserApi;
use App\Models\UserApiColumn;
use App\Models\UserApiData;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Inertia\Inertia;

class ApiService2
{
    //

    public function store($request)
    {

        
        // Criar a API (UserApi)
        $user = auth()->user();
        $user_id = $user->id;
        $apiName = $request->input('apiName');
        $columns = $request->input('columns');
    
        // Criar a entrada na tabela user_apis
        $userApi = UserApi::create([
            'user_id' => $user_id,
            'api_name' => $apiName,
            'created_at' => now(),
            'updated_at' => now()
        
        ]);
    
        // Criar as colunas para essa API (UserApiColumn)
        foreach ($columns as $column) {
            UserApiColumn::create([
                'user_api_id' => $userApi->id,
                'name' => $column['name'],
                'type' => $column['type'],
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    
        // Retornar a resposta de sucesso
        return response()->json([
            'status' => 'success',
            'message' => 'API e colunas criadas com sucesso!',
            'apiName' => $apiName,
        ]);
    }

    public function show($id)
    {
        $api = UserApi::with('user')->findOrFail($id); 
    
        // Buscar os dados relacionados a essa API
        $data = UserApiData::where('user_api_id', $api->id)->paginate(10); // Paginação com 10 itens por página
        $columns = UserApiColumn::where('user_api_id', $api->id)->get();
        // dd($columns);
        return Inertia::render('api/Show', [
            'api' => $api,
            'data' => $data, 
            'columns' => $columns
        ]);
    }
    
    
    

  
}
