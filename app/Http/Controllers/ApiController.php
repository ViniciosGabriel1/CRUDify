<?php

namespace App\Http\Controllers;

use App\Services\ApiService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Inertia\Inertia;




class ApiController extends Controller
{
    //
    private $apiService;


    public function __construct(ApiService $apiService)
    {
        $this->apiService = $apiService;
    }


    public function create() {
        // dd(session()->all());

        return Inertia::render('api/Create');
    }

    public function store1(Request $request){

         $this->apiService->connectToSQLite();

         
    }


    public function store(Request $request)
{
    $validated = $request->validate([
        'apiName' => 'required|string|max:255',
        'columns' => 'required|array',
        'columns.*.name' => 'required|string',
        'columns.*.type' => 'required|string|in:string,integer,text,boolean,timestamp',
    ]);

    // Criar banco de dados e migrations
    $this->apiService->createSQLiteDatabase($request);

    $user = auth()->user();
    $user_id = $user->id;
    $user_name = $user->name;

    // Rodar migrations no banco SQLite do usuário
    $this->apiService->runUserMigrations($user_id, $user_name);

    return response()->json([
        'message' => 'Banco de dados criado e migration executada!',
    ]);
}

}
