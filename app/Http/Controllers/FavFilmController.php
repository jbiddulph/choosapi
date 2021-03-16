<?php

namespace App\Http\Controllers;

use App\Models\FavFilm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FavFilmController extends Controller
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
        //
        $faves = $this->user
		->favFilm()
        ->get(['id', 'Poster', 'Title', 'Type', 'Year', 'imdbID']);
        return response()->json($todos->toArray());
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        //
        $validator = Validator::make($request->all(), [
            'Poster' => 'required|string',
            'Title' => 'required|string',
            'Type' => 'required|string'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 400);
        }

        $fave = new FavFilm();
        $fave->Poster = $request->Poster;
        $fave->Title = $request->Title;
        $fave->Type = $request->Type;
        $fave->Year = $request->Year;
        $fave->imdbID = $request->imdbID;

        if($this->user->faves()->save($fave)){
            return response()->json([
                'status' => true,
                'fave' => $fave
                ]
            );
        } else {
            return response()->json([
                'status' => false,
                'message' => 'oops the todo could not be saved'
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FavFilm  $favFilm
     * @return \Illuminate\Http\Response
     */
    public function show(FavFilm $favFilm)
    {
        //
        return $favFilm;
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FavFilm  $favFilm
     * @return \Illuminate\Http\Response
     */
    public function destroy(FavFilm $favFilm)
    {
        //
        if($favFilm->delete()) {
            return response()->json([
                'status' => true,
                'favfilm' => $favFilm
                ]
            );
        } else {
            return response()->json([
                'status' => false,
                'message' => 'oops the Fav could not be deleted'
            ]);
        }
    }

    protected function guard()
    {
        return Auth::guard();

    }
}
