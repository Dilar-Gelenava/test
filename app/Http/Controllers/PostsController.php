<?php

namespace App\Http\Controllers;

use App\Comments;
use App\Likes;
use App\Posts;
use App\User;
use App\UserData;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PostsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param $userId
     * @return Application|Factory|Response|View
     */
    public function index($userId)
    {
        $user_name = User::where('id', $userId)->firstOrFail();
        $posts = collect(Posts::where('user_id', $userId)->get())->sortByDesc('id');

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
            'user_name' => $user_name[0]->name,
        ]);

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
     * @return RedirectResponse
     */
    public function store_post(Request $request)
    {
        if($request->hasFile('image'))
        {
            $image = $request->file('image');
            $extension = $image->getClientOriginalExtension();
            $image_name = auth()->user()->id.'-'.time().'.'.$extension;
            $image->storeAs('public/post_images', $image_name);
            $image_url = "storage/post_images/".$image_name;
        } else {
            $image_url = Null;
        }
        Posts::create([
            "title"=>$request->input("title"),
            "description"=>$request->input("description"),
            "user_id"=>auth()->user()->id,
            "image_url"=>$image_url
        ]);
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     * @param $postId
     * @return Application|Factory|Response|View
     */
    public function show_post($postId)
    {
        $post = Posts::where("id",$postId)->firstOrFail();
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
        return view("layouts/view_post",
            ['post' => $post]
        );
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param Request $request
     * @return Application|Factory|Response|View
     */
    public function edit_post(Request $request)
    {
        $post = Posts::where("id", $request->input("id"))->firstOrFail();
        return view("forms/edit_post")
            ->with(['post' => $post]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @return array|Application|ResponseFactory|RedirectResponse|Response|string
     */
    public function update_post(Request $request)
    {
        $post_id = $request->post_id;
        if($request->hasFile('image'))
        {
            $image_url = DB::table('posts')
                ->where('id', '=', $post_id)->select("image_url")->get()
                ->map(function ($post) {
                    return $post->image_url;})[0];
            Storage::delete(str_replace('storage', 'public', $image_url));
            $image = $request->file('image');
            $extension = $image->getClientOriginalExtension();
            $image_name = auth()->user()->id.'-'.time().'.'.$extension;
            $image->storeAs('public/post_images', $image_name);
            $image_url = "storage/post_images/".$image_name;
            Posts::where("id", $post_id)
                ->update([
                    'image_url'=>$image_url
                ]);
        }
        Posts::where("id", $post_id)
            ->update([
            "title"=>$request->input("title"),
            "description"=>$request->input("description"),
        ]);
        return redirect('home');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @return string
     */
    public function destroy_post(Request $request)
    {
        $post_id = $request->input("postId");
        $image_url = DB::table('posts')
            ->where('id', '=', $post_id)->select("image_url")->get()
            ->map(function ($post) {
                return $post->image_url;})[0];
        Storage::delete(str_replace('storage', 'public', $image_url));
        Posts::where("id", $post_id)->delete();
        Comments::where("post_id", $post_id)->delete();
        Likes::where('post_id', $post_id)->delete();

        return redirect('/home');
    }
}
