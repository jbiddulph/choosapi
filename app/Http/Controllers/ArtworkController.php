<?php

namespace App\Http\Controllers;

use App\Models\Artwork;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ArtworkController extends Controller
{
    
    protected $user;

    public function __construct() 
    {
        $this->middleware('auth:api');
        $this->user = $this->guard()->user();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $artworks = $this->user
		->artworks()
		->when(request()->has('limit'), fn ($builder) => $builder->take(request('limit')))
        ->get(['id', 'title', 'description', 'status', 'primary_art', 'height', 'width', 'cost', 'live']);
        return response()->json($artworks->toArray());
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user_id = auth()->user()->id;
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'description' => 'required|string'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 400);
        }

        $artwork = new Artwork();
        $artwork->title = $request->title;
        $artwork->description = $request->description;
        $artwork->status = $request->status;
        // $artwork->primary_art = $request->primary_art;
        $request->validate([
            'primary_art' => 'required:mimes:jpeg,jpg,png,gif',
        ]);
        if($request->hasFile('primary_art')){
            $file = $request->file('primary_art');
            $ext = $file->getClientOriginalExtension();
            $filename = time().'.'.$ext;
            $file->move('uploads/gallery/', $filename);
            // User::where('id',$user_id)->update([
            //     'primary_art'=>$filename
            // ]);
            $artwork->primary_art = $filename;
        } else {
            $artwork->primary_art = $request->primary_art;
        }
        $artwork->height = $request->height;
        $artwork->width = $request->width;
        $artwork->cost = $request->cost;
        $artwork->live = $request->live;

        if($this->user->artworks()->save($artwork)){
            return response()->json([
                'status' => true,
                'artwork' => $artwork
                ]
            );
        } else {
            return response()->json([
                'status' => false,
                'message' => 'oops the artwork could not be saved'
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Artwork  $artwork
     * @return \Illuminate\Http\Response
     */
    public function show(Artwork $artwork)
    {
        return $artwork;
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\artwork  $artwork
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, artwork $artwork)
    {
        //
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'description' => 'required|string',
            'live' => 'required|boolean'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 400);
        }

        $artwork->title = $request->title;
        $artwork->description = $request->description;
        $artwork->status = $request->status;
        $artwork->primary_art = $request->primary_art;
        $artwork->height = $request->height;
        $artwork->width = $request->width;
        $artwork->cost = $request->cost;
        $artwork->live = $request->live;

        if($this->user->artworks()->save($artwork)){
            return response()->json([
                'status' => true,
                'artwork' => $artwork
                ]
            );
        } else {
            return response()->json([
                'status' => false,
                'message' => 'oops the artwork could not be saved'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\artwork  $artwork
     * @return \Illuminate\Http\Response
     */
    public function destroy(artwork $artwork)
    {
        if($artwork->delete()) {
            return response()->json([
                'status' => true,
                'artwork' => $artwork
                ]
            );
        } else {
            return response()->json([
                'status' => false,
                'message' => 'oops the artwork could not be deleted'
            ]);
        }
    }

    protected function guard()
    {
        return Auth::guard();

    }
}
