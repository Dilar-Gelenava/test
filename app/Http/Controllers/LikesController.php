<?php

namespace App\Http\Controllers;

use App\Comments;
use App\Likes;
use App\Posts;
use Exception;
use http\Env\Response;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LikesController extends Controller
{
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Application|ResponseFactory|RedirectResponse|\Illuminate\Http\Response
     * @throws Exception
     */
    public function store_like(Request $request)
    {

        $like = Likes::all()
            ->where('post_id', $request->input("postId"))
            ->where('user_id', auth()->user()->id)->first();


        if (empty($like)) {
            Likes::create([
                "user_id"=>auth()->user()->id,
                "post_id"=>$request->input("postId"),
                "is_like"=>$request->input("is_like"),
            ]);
        } else {
            if ($like->is_like == $request->input("is_like")){
                $like->delete();
            } else {
                $like->update([
                    "is_like"=>$request->input("is_like"),
                ]);
            }
        }

        $user_likes = Likes::all()->where('post_id', $request->input("postId"));
        $post_likes = 0;
        $post_dislikes = 0;
        foreach ($user_likes as $like) {
            if ($like->is_like) {
                $post_likes += 1;
            } else {
                $post_dislikes += 1;
            }
        }

        Posts::where('id', $request->input("postId"))
            ->update([
                'likes'=>$post_likes,
                'dislikes'=>$post_dislikes,
            ]);


        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
