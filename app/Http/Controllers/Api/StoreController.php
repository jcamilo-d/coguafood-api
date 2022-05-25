<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Store;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\Store as StoreResource;
use App\Http\Resources\StoreCollection;
// use App\Http\Resources\StoreCategoryResource;
use App\Http\Controllers\Controller;

class EventController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth:admins', ['except' => ['store', 'show', 'update', 'destroy']]);
        // $this->middleware('auth:users', ['except' => ['index', 'storeByAdmin', 'showByAdmin', 'updateByAdmin', 'destroyByAdmin', 'showAll']]);
    }

    public function index()
    {
        return new StoreCollection(Store::paginate());
    }

    public function store(Request $request)
    {
        $data = $request->json()->all();

        $store = Store::create([
            'name' => $data['name'],
            'location' => $data['location'],
            'description' => $data['description'],
            'cellphone' => $data['cellphone'],
            'email' => $data['email'],
            'category_id' => $data['category_id'],
        ]);

        return response()->json(new StoreResource($store), 201);
    }

    public function storeByAdmin(Request $request)
    {
        $data = $request->json()->all();

        $store = Store::create([
            'name' => $data['name'],
            'location' => $data['location'],
            'description' => $data['description'],
            'cellphone' => $data['cellphone'],
            'email' => $data['email'],
            'category_id' => $data['category_id'],
        ]);

        return response()->json(new StoreResource($store), 201);
    }

    public function showAll(Request $request)
    {
        return StoreResource::collection(Store::all());
    }

    public function show(int $id, Request $request)
    {
        if (!$request->isJson()) {
            return response()->json(["error" => $request], 400);
        }

        $store = Store::findOrFail($id);

        return response()->json(new StoreResource($store), 200);
    }

    public function update(int $id, Request $request)
    {
        if(!$request->isJson()) {
            return response()->json(["error" => $request], 400);
        }

        $data = $request->json()->all();


        $store = Store::findOrFail($id);


        $store->update([
            'name' => $data['name'],
            'location' => $data['location'],
            'description' => $data['description'],
            'cellphone' => $data['cellphone'],
            'email' => $data['email'],
            'category_id' => $data['category_id'],
        ]);

        return response()->json(new StoreResource($store), 200);
    }

    public function destroy($id)
    {

        $store = Store::findOrFail($id);

        $store->delete();
        return response()->json(null, 204);
    }

    public function destroyByAdmin($id)
    {
        $store = Store::findOrFail($id);

        $store->delete();
        return response()->json(null, 204);
    }
}

