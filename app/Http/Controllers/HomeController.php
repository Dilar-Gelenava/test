<?php

namespace App\Http\Controllers;

use App\Followers;
use App\Likes;
use App\User;
use App\UserData;
use Illuminate\Http\Request;
use App\Posts;
use App\Comments;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }


    /**
     * Show the application dashboard.
     *
//     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

//        $posts = DB::table('posts')
//            ->join('followers', 'posts.user_id', '=', 'followers.follower_id')
//            ->where('follower_id', auth()->id())->get();

        $posts = Posts::all()->sortByDesc('id')->take(100);

        $posts = collect($posts);


        foreach ($posts as $post) {
            $comments = collect(DB::table('comments')
                ->where('post_id', '=', $post->id)->get())->sortByDesc('id');
            $user_name = DB::table('users')
                ->where('id', '=', $post->user_id)
                ->select('name')
                ->get();

            foreach ($comments as $comment) {
                $user = User::where('id', $comment->user_id)->get()[0];
                $comment->user_name = $user->name;
                $comment->user_id = $user->id;
            }

            $liked_users = Likes::all()->where('post_id', $post->id)->where('user_id', auth()->user()->id)->first();
            $post->liked_users = $liked_users;
            $post->comments = $comments;
            $post->user_name = $user_name[0]->name;

        }

        return view("home", [
            'posts' => $posts,
        ]);

    }
}
