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
        $apiName = $request->input('api_name');
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

        return redirect()->route('panel.api.create');

      
    }

    public function show($id)
    {
        $api = UserApi::with('user')->findOrFail($id);

        // Buscar os dados relacionados a essa API
        $data = UserApiData::where('user_api_id', $api->id)->paginate(10); // Paginação com 10 itens por página
        $columns = UserApiColumn::where('user_api_id', $api->id)->get();
        // dd($data);
        return Inertia::render('api/Show', [
            'api' => $api,
            'data' => $data,
            'columns' => $columns
        ]);
    }

    public function edit($id)
    {
        $api = UserApi::with('user')->findOrFail($id);

        // Buscar os dados relacionados a essa API
        $columns = UserApiColumn::where('user_api_id', $api->id)->get();
        // dd($data);
        return Inertia::render('api/Edit', [
            'api' => $api,
            'columns' => $columns
        ]);
    }


    public function update($id,$request){
        $api = UserApi::with('user')->findOrFail($id);

        $api->update([
            'api_name' => $request->api_name
        ]);

        $api->columns()->delete();

        foreach($request->columns as $column){
            $api->columns()->create([
                'name' => $column['name'],
                'type' => $column['type']
            ]);
        }

        return redirect()->route('panel.api.edit',$id);



    }


    public function destroy($id){
        // dd($id);
        // $id = 16;
        $api = UserApi::with('user')->findOrFail($id);
        
        $api->delete();

        return redirect()->route('dashboard');

    }

    public function teste($id){

        $api = UserApi::with('user')->findOrFail($id);

        // Buscar os dados relacionados a essa API
            $columns = UserApiColumn::where('user_api_id', $api->id)->get();
        // dd($data);
        return Inertia::render('api/Teste', [
            'api' => $api,
            'columns' => $columns
        ]);
    }
}
