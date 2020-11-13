<?php

namespace App\Http\Controllers;

use App\Followers;
use App\Likes;
use App\Posts;
use App\UserData;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param $userName
     * @param $userId
     * @return Application|Factory|View|void
     */
    public function index($userId, $userName)
    {

        $user = DB::table('users')->where('id', $userId)->where('name', $userName)->first();
        if (!empty($user)){
            $user_data = UserData::where('user_id', $userId)->first();
            $posts = Posts::all()->where('user_id', $userId)->sortByDesc('id');
            foreach ($posts as $post) {
                $comments = collect(DB::table('comments')
                    ->where('post_id', '=', $post->id)->get())->sortByDesc('id');
                $liked_users = Likes::all()->where('post_id', $post->id)->where('user_id', auth()->user()->id)->first();
                $post->liked_users = $liked_users;
                $post->comments = $comments;
                $post->user_name = $userName;
            }

            $following = !empty(Followers::all()
                ->where('follower_id', auth()->user()->id)
                ->where('following_id', $userId)->first());


            if (empty($user_data)) {
                UserData::create([
                    "user_id" => $userId,
                ]);
                $user_data = UserData::where('user_id', $userId)->first();
                $profile_picture_url = '../storage/profile_pictures/blank.png';
            } else {
                $profile_picture_url = $user_data->profile_picture_url;
            }

            $user_followers = DB::table('followers')
                ->join('users', 'users.id', '=', 'followers.following_id')
                ->where('followers.following_id', $userId)
                ->get();
            foreach ($user_followers as $user_follower) {
                $follower_name = DB::table('users')
                    ->where('id', $user_follower->follower_id)
                    ->first()->name;
                $user_follower->name = $follower_name;
            }

            $user_follows = DB::table('followers')
                ->join('users', 'users.id', '=', 'followers.follower_id')
                ->where('followers.follower_id', $userId)
                ->get();
            foreach ($user_follows as $user_follow) {
                $follow_name = DB::table('users')
                    ->where('id', $user_follow->following_id)
                    ->first()->name;
                $user_follow->name = $follow_name;
            }


            return view("profile", [
                'user_followers' => $user_followers,
                'user_follows' => $user_follows,
                'following' => $following,
                'user_id' => $userId,
                'posts' => $posts,
                'user_data' => $user_data,
                'user_image' => 1,
                'user_name' => $user->name,
                'profile_picture_url' => $profile_picture_url,
            ]);

        } else {
            return abort(404);
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Application|ResponseFactory|RedirectResponse|Response
     */
    public function store_user_data(Request $request)
    {

        if($request->hasFile('image'))
        {
            $image = $request->file('image');
            $extension = 'jpg';
            $image_name = $request->input('userId').'.'.$extension;
            $image->storeAs('public/profile_pictures', $image_name);
            $image_url = "storage/profile_pictures/".$image_name;
        } else {
            $image_url = 'storage/profile_pictures/blank.png';
        }

        $user_data = UserData::where('user_id', $request->input('userId'))->get();
        if (count($user_data) == 0) {
            UserData::create([
                'user_id'=>$request->input('userId'),
                'first_name'=>$request->input('firstName'),
                'last_name'=>$request->input('lastName'),
                'bio'=>$request->input('bio'),
                'birthday'=>$request->input('birthday'),
                'address'=>$request->input('address'),
                'profile_picture_url'=>$image_url,
            ]);
        } else {
            UserData::where("user_id", $request->input('userId'))
                ->update([
                    'first_name'=>$request->input('firstName'),
                    'last_name'=>$request->input('lastName'),
                    'bio'=>$request->input('bio'),
                    'birthday'=>$request->input('birthday'),
                    'address'=>$request->input('address'),
                ]);
            if ($request->hasFile('image')) {
                UserData::where("user_id", $request->input('userId'))
                    ->update([
                        'profile_picture_url'=>$image_url,
                    ]);
            }
        }

        return redirect()->back();

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
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
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
