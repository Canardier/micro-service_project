<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserCreation;
use App\Http\Requests\UserLogin;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['store', 'login', 'show']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param UserCreation $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(UserCreation $request)
    {
        $user = User::create([
            'username' => $request->get('username'),
            'pseudo' => $request->get('pseudo'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password')),
        ]);

        return response()->json([
            'message' => 'Ok',
            'data' => UserResource::make($user),
        ], 201);
    }

    /**
     * Display the specified resource.
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $users = DB::table('users');
        if ($request->has("pseudo"))
            $users = DB::table('users')->where("pseudo", $request->get("pseudo"));

        $pagination = $users->Paginate($request->has("perPage") ? $request->get("perPage") : 5);

        if ($request->has("page"))
            if ($request->get("page") == 0 || $request->get("page") > $pagination->lastPage())
                return response()->json(null, 400);


        return response()->json([
            'message' => 'OK',
            'data' => $pagination->items(),
            "pager" => collect([
                "current" => $pagination->currentPage(),
                "total" => $pagination->lastPage()
            ])
        ], 200);
    }

    /**
     * Display the specified resource.
     * @param User $id
     * @return \Illuminate\Http\Response
     */
    public function show_by_id(User $id)
    {
        return response()->json([
            'message' => 'OK',
            'data' => $id
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param User $id
     * @return \Illuminate\Http\Response
     */
    public function update(User $id, Request $request)
    {
        if ($request->has("username"))
            $id->username = $request->get("username");
        if ($request->has("pseudo"))
            $id->pseudo = $request->get("pseudo");
        if ($request->has("email"))
            $id->email = $request->get("email");
        if ($request->has("password"))
            $id->password = Hash::make($request->get('password'));
        $id->save();

        return response()->json([
            'message' => 'Ok',
            'data' => UserResource::make($id),
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function destroy(User $id)
    {
        if (auth()->user()->id !== $id->id) {
            return response()->json([
                'message' => 'Forbidden',
                'code' => '403',
            ], 403);
        }

        $id->delete();

        return response('', 201);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(UserLogin $request)
    {

        $token = auth()->attempt([
            'email' => $request->get('login'),
            'password' => $request->get('password')
        ]);

        if (!$token) {
            return response()->json([
                'message' => 'Bad credentials',
                'code' => '401',
                'data' => [
                    'login' => [
                        "The login or password is incorrect."
                    ]
                ],
            ], 401);
        }

        return response()->json([
            'message' => 'Ok',
            'data' => [
                'token' => $token,
                'user' => UserResource::make(auth()->user())
            ],
        ], 201);
    }
}
