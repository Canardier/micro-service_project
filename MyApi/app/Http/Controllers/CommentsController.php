<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentsCreation;
use App\Http\Resources\CommentsResource;
use App\Models\Comments;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommentsController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
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
     * @param CommentsCreation $request
     * @param Video $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Video $id, CommentsCreation $request)
    {
        $comment = new Comments([
            'users_id' => auth()->user()->id,
            'body' => $request->get('body')
        ]);

        $id->comments()->save($comment);

        return response()->json([
            'message' => 'Ok',
            'data' => CommentsResource::make($comment),
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function show(Video $id, Request $request)
    {
        $comments = DB::table('comments')->where('video_id',$id->id);
        $pagination = $comments->Paginate($request->has("perPage") ? $request->get("perPage") : 5);

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

}
