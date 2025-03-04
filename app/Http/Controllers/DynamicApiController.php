<?php

namespace App\Http\Controllers;

use App\Models\UserApi;
use App\Models\UserApiColumn;
use App\Models\UserApiData;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class DynamicApiController extends Controller
{
    // Criação de dados para uma API específica
    public function store($apiName, Request $request)
    {
        // Verifique se a API existe
        $api = UserApi::where('api_name', $apiName)->first();

        if (!$api) {
            return response()->json(['message' => 'API não encontrada'], 404);
        }

        // Pegamos as colunas cadastradas para essa API
        $allowedColumns = UserApiColumn::where('user_api_id', $api->id)
            ->pluck('type', 'name') // Retorna ['coluna1' => 'tipo1', 'coluna2' => 'tipo2']
            ->toArray();

        // Filtramos os dados recebidos para aceitar apenas as colunas permitidas
        $filteredData = [];
        foreach ($request->all() as $key => $value) {
            if (array_key_exists($key, $allowedColumns)) {
                // Se quiser validar tipos, pode fazer aqui antes de adicionar ao array
                $filteredData[$key] = $value;
            }
        }

        // Verificamos se algum dado foi aceito antes de armazenar
        if (empty($filteredData)) {
            return response()->json(['message' => 'Nenhum dado válido foi enviado'], 400);
        }

        // Armazenar dados
        UserApiData::create([
            'user_api_id' => $api->id,
            'user_id' => $api->user_id,
            'data' => json_encode($filteredData),
        ]);

        return response()->json(['message' => 'Dados armazenados com sucesso'], 200);
    }


    // Leitura de dados de uma API específica
    public function index($apiName)
    {
        // Verifique se a API existe
        $api = UserApi::where('api_name', $apiName)->first();
        if (!$api) {
            return response()->json(['message' => 'API não encontrada'], 404);
        }

        // Obter todos os dados relacionados à API
        $data = UserApiData::where('user_api_id', $api->id)->get();

        return response()->json(['data' => $data], 200);
    }

    // public function show($apiName, $id)
    // {
    //     $api = UserApi::where('api_name', $apiName)->first();
    //     if (!$api) {
    //         return response()->json(['message' => 'API não encontrada'], 404);
    //     }
    //     $data = UserApiData::where('user_api_id', $api->id)->get();

    //     // dd($data);

    //     if (!$data) {
    //         return response()->json(['message' => 'Dados não encontrados'], 404);
    //     }

    //     return response()->json(['data' => $data], 200);
    // }


    // Atualização de dados de uma API específica
    public function update($apiName, $id, Request $request)
    {
        // Verifique se a API existe
        $api = UserApi::where('api_name', $apiName)->first();
        if (!$api) {
            return response()->json(['message' => 'API não encontrada'], 404);
        }

        // Buscar o dado
        $data = UserApiData::where('user_api_id', $api->id)->where('id', $id)->first();
        if (!$data) {
            return response()->json(['message' => 'Dado não encontrado'], 404);
        }

        // Validação dos dados (ajustar conforme os campos da API)
        $validated = $request->validate([
            'data' => 'required|array',
        ]);

        // Atualizar o dado
        $data->update([
            'data' => json_encode($validated['data']),
        ]);

        return response()->json(['message' => 'Dados atualizados com sucesso'], 200);
    }

    // Exclusão de dados de uma API específica
    public function destroy($apiName, $id)
    {
        // Verifique se a API existe
        $api = UserApi::where('api_name', $apiName)->first();
        if (!$api) {
            return response()->json(['message' => 'API não encontrada'], 404);
        }

        // Buscar o dado
        $data = UserApiData::where('user_api_id', $api->id)->where('id', $id)->first();
        if (!$data) {
            return response()->json(['message' => 'Dado não encontrado'], 404);
        }

        // Excluir o dado
        $data->delete();

        return response()->json(['message' => 'Dado excluído com sucesso'], 200);
    }
}
