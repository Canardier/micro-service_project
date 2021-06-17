<?php

namespace App\Http\Controllers;

use App\Http\Requests\VideoEncoding;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use finfo;
use Illuminate\Http\Request;

use App\Http\Requests\VideoCreation;
use App\Http\Resources\VideoRessource;
use App\Models\Video;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileCannotBeAdded;
use Owenoj\LaravelGetId3\GetId3;

class VideoController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api')->except(["show", "show_by_id", "encode"]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param VideoCreation $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(User $id, VideoCreation $request)
    {

        if ($request->hasFile('source')) {
            $track = GetId3::fromUploadedFile(request()->file('source'));

            $video = Video::create([
                'name' => $request->get('name'),
                'duration' => $track->getPlaytimeSeconds(),
                'user_id' => $id->id
            ]);

            try {
                $video->addMedia($request->file('source'))->toMediaCollection('videos');
            } catch (FileCannotBeAdded $e) {
                return response()->json([
                    'message' => 'File cannot be downloaded',
                    'code' => '400',
                ], 400);
            }
        } else {
            $video = Video::create([
                'name' => $request->get('name'),
                'user_id' => $id->id
            ]);
        }

        return response()->json([
            'message' => 'Ok',
            'data' => VideoRessource::make($video),
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request)
    {
        $videos = DB::table('videos');
        if ($request->has("name"))
            $videos = $videos->where("name", $request->get("name"));
        if ($request->has("user"))
            $videos = $videos->where("user_id", $request->get("user"));
        if ($request->has("duration"))
            $videos = $videos->where("duration", $request->get("duration"));

        $pagination = $videos->Paginate($request->has("perPage") ? $request->get("perPage") : 5);

        if ($request->has("page"))
            if ($request->get("page") == 0 || $request->get("page") > $pagination->lastPage())
                return response()->json(null, 400);

            $toRender = [];
            foreach ($pagination->items() as $vid) {
                array_push($toRender, VideoRessource::make(Video::find($vid->id)));
            }

        return response()->json([
            'message' => 'OK',
            'data' => $toRender,
            "pager" => collect([
                "current" => $pagination->currentPage(),
                "total" => $pagination->lastPage()
            ])
        ], 200);

    }

    /**
     * Display the specified resource.
     * @param User $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function show_by_id(User $id, Request $request)
    {
        $videos =  $id->video()->paginate($request->has("perPage") ? $request->get("perPage") : 5);

        if ($request->has("page"))
            if ($request->get("page") == 0 || $request->get("page") > $videos->lastPage())
                return response()->json(null, 400);


        return response()->json([
            'message' => 'OK',
            'data' => VideoRessource::collection($videos),
            "pager" => collect([
                "current" => $videos->currentPage(),
                "total" => $videos->lastPage()
            ])
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Video $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Video $id)
    {
        if (!auth()->user()->id) {
            return response()->json([
                'message' => 'Forbidden',
                'code' => '403',
            ], 403);
        }
        if ($request->has("name"))
            $id->name = $request->get("name");
        if ($request->has('user'))
            $id->user_id = $request->get('user');
        $id->save();

        return response()->json([
            'message' => 'Ok',
            'data' => VideoRessource::make($id),
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Video $id)
    {
        $id->delete();
        return response()->json('', 204);
    }

    /**
     * Encode video by id
     *
     * @param VideoCreation $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\FileCannotBeAdded
     */
    public function encode(Video $id, VideoEncoding $request)
    {
        $supported_format = ['1080', '720', '480', '360', '240', '144'];
        $supported_mimes = ['video/avi', 'video/mpeg', 'video/quicktime', 'video/mp4'];

        $file_info = new finfo(FILEINFO_MIME_TYPE);
        $mime_type = $file_info->buffer(file_get_contents($request->get('file')));

        if (!in_array($request->get('format'), $supported_format) || !in_array($mime_type, $supported_mimes)) {
            return response()->json([
                'message' => 'Video format not supported',
                'code' => 10001,
            ], 400);
        }

        try {
            $id->addMediaFromUrl($request->get('file'))->withCustomProperties(['format' => $request->get('format')])->toMediaCollection('videos');
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'File cannot be downloaded',
                'code' => '400',
            ], 400);
        }

        return response()->json([
            'message' => 'OK',
            'data' => VideoRessource::make($id),
        ], 201);
    }
}
