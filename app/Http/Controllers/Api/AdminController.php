<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\Admin as AdminResource;
use App\Http\Resources\AdminCollection;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth:admins', ['except' => ['login']]);
    }

    public function index(Request $request)
    {
        return new AdminCollection(Admin::paginate());
    }

    public function store(Request $request)
    {
        if(!$request->isJson()) {
            return response()->json(["error" => $request], 400);
        }

        $data = $request->json()->all();

        $adminExist = Admin::where('email', '=', $data['email'])
            ->first();
        
        if($adminExist) {
            return response()->json(["error" => "El registro ya existe"], 409);
        }

        $admin = Admin::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => app('hash')->make($data['password'])
        ]);

        return response()->json(new AdminResource($admin), 201);
    }

    public function show(int $id, Request $request)
    {
        if(!$request->isJson()) {
            return response()->json(["error" => $request], 400);
        }

        $admin = Admin::findOrFail($id);

        return response()->json(new AdminResource($admin), 200);
    }

    public function update(int $id, Request $request)
    {
        if(!$request->isJson()) {
            return response()->json(["error" => $request], 400);
        }

        $data = $request->json()->all();

        $admin = Admin::findOrFail($id);

        if(isset($data['password'])) {
            $data['password'] = app('hash')->make($data['password']);
        }

        $admin->update($data);

        return response()->json(new AdminResource($admin), 200);
    }

    public function destroy(int $id, Request $request)
    {
        $admin = Admin::findOrFail($id);

        $admin->update([
            'account_state' => 0
        ]);

        return response()->json(null, 204);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @param Request $request
     * @return Response
     */
    public function login(Request $request)
    {
        if(!$request->isJson()) {
            return response()->json(["error" => $request], 400);
        }

        $data = $request->json()->all();

        $credentials = array(
            'email' => $data['email'],
            'password' => $data['password']
        );

        if (!$token = Auth::guard('admins')->attempt(['email' => $data['email'], 'password' => $data['password']])) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get authenticated admin details.
     *
     * @param Request $request
     * @return Response
     */
    public function me()
    {
        $user = Auth::guard('admins')->user();
        return response()->json(new AdminResource($user));
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        Auth::guard('admins')->logout();
        return response()->json(['message' => 'Successfully logged out'], 200);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(Auth::guard('admins')->refresh());
    }
}
