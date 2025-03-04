<?php

namespace App\Http\Controllers;

use App\Models\UserApi;
use App\Services\ApiService;
use App\Services\ApiService2;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Inertia\Inertia;




class ApiController extends Controller
{
    //
    private $apiService;
    private $apiService2;


    public function __construct(ApiService $apiService,ApiService2 $apiService2)
    {
        $this->apiService2 = $apiService2;
        $this->apiService = $apiService;
    }


    public function index()
    {
        $apis = UserApi::with('user')->get(); 
        // dd($apis);
        return Inertia::render('Dashboard', [
            'apis' => $apis
        ]);
        // return Inertia::render('Dashboard');
    }
    


    public function create() {
        // dd(session()->all());

        return Inertia::render('api/Create');
    }

    public function store(Request $request){
        // dd($request);
        return $this->apiService2->store($request);
         
    }


    public function show($id){
        
        return $this->apiService2->show($id);
    }




    


//     public function store(Request $request)
// {
//     $validated = $request->validate([
//         'apiName' => 'required|string|max:255',
//         'columns' => 'required|array',
//         'columns.*.name' => 'required|string',
//         'columns.*.type' => 'required|string|in:string,integer,text,boolean,timestamp',
//     ]);

//     // Criar banco de dados e migrations
//     return $this->apiService->store_service($request);


// }

}
