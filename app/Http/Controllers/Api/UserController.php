<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\User as UserResource;
use App\Http\Resources\UserEventsResource;
use App\Http\Resources\UserCollection;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth:admins', ['except' => ['login', 'showAll', 'logout', 'refresh', 'me', 'meWithEvents', 'updateMe']]);
        $this->middleware('auth:admins', ['except' => [ 'store', 'showAll']]);
        // $this->middleware('auth:users', ['except' => ['store', 'login', 'index', 'showAll', 'show', 'showWithEvents', 'update', 'destroy']]);
        $this->middleware('auth:users', ['except' => ['store', 'login']]);
    }

    public function index(Request $request)
    {
        return new UserCollection(User::paginate());
    }

    public function showAll(Request $request)
    {
        return new UserCollection(User::all());
    }

    public function store(Request $request)
    {
        if (!$request->isJson()) {
            return response()->json(["error" => $request], 400);
        }

        $data = $request->json()->all();

        $userExist = User::where('email', '=', $data['email'])
            ->first();

        if ($userExist) {
            return response()->json(["error" => "El usuario ya existe"], 409);
        }

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => app('hash')->make($data['password'])
        ]);

        return response()->json(new UserResource($user), 201);
    }

    public function show(int $id, Request $request)
    {
        if (!$request->isJson()) {
            return response()->json(["error" => $request], 400);
        }

        $user = User::findOrFail($id);

        return response()->json(new UserResource($user), 200);
    }

    public function update(int $id, Request $request)
    {
        if (!$request->isJson()) {
            return response()->json(["error" => $request], 400);
        }

        $data = $request->json()->all();

        $userExist = User::findOrFail($id);

        if (isset($data['password'])) {
            $data['password'] = app('hash')->make($data['password']);
        }

        $userExist->update($data);

        return response()->json(new UserResource($userExist), 200);
    }

    public function updateMe(Request $request)
    {
        if (!$request->isJson()) {
            return response()->json(["error" => $request], 400);
        }

        $data = $request->json()->all();

        $user = Auth::guard('users')->user();

        if (isset($data['password'])) {
            $data['password'] = app('hash')->make($data['password']);
        }

        $user->update($data);

        return response()->json(new UserResource($user), 200);
    }

    public function destroy(int $id, Request $request)
    {
        $user = User::findOrFail($id);

        $user->update([
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
        if (!$request->isJson()) {
            return response()->json(["error" => $request], 400);
        }

        $data = $request->json()->all();

        if (!$token = Auth::guard('users')->attempt(['email' => $data['email'], 'password' => $data['password']])) {
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
        $user = Auth::guard('users')->user();
        return response()->json(new UserResource($user));
    }

    /**
     * Get authenticated admin details with events.
     *
     * @param Request $request
     * @return Response
     */
    public function meWithEvents()
    {
        $user = Auth::guard('users')->user();
        $authenticatedUserWithEvents = new UserEventsResource($user);
        return response()->json($authenticatedUserWithEvents, 200);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        Auth::guard('users')->logout();
        return response()->json(['message' => 'Successfully logged out'], 200);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(Auth::guard('users')->refresh());
    }
}

