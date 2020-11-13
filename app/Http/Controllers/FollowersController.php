<?php

namespace App\Http\Controllers;

use App\Followers;
use App\Posts;
use App\User;
use App\UserData;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class FollowersController extends Controller
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
     * @return RedirectResponse
     */
    public function follow(Request $request)
    {

        $follower_id = auth()->user()->id;
        $following_id = $request->input('following_id');

        $user_exists = !empty(User::where('id', $following_id)->first());

        if ($user_exists){
            $following = Followers::all()
                ->where('follower_id', $follower_id)
                ->where('following_id', $following_id)->first();

            if (empty($following)) {
                Followers::create([
                    "follower_id"=>$follower_id,
                    "following_id"=>$following_id,
                ]);
            } else {
                Followers::where('id', $following->id)->delete();
            }

            $following_count = count(Followers::where('following_id', $following_id)->get());
            UserData::where('user_id', $follower_id)->update([
                'following' => $following_count,
            ]);

            $followers_count = count(Followers::where('following_id', $following_id)->get());
            UserData::where('user_id', $following_id)->update([
                'followers' => $followers_count,
            ]);

        }

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
     * @param  \Illuminate\Http\Request  $request
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
