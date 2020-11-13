@extends('layouts.app')

@section('content')
<div class="col-md-8">
    <div class="card" style="background-color: transparent">
        <div class="card-header" style="background-color: purple;">
            Edit your post
        </div>
        <div class="container" style="background-color: #646464; border-radius: 0 0 15px 15px">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif
            <div id="postForm" class="container col-lg-offset-4 col-lg-4" style="margin: 20px;">
                <form action="{{ route("updatePost", ["post_id" => $post->id])}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="text" class="form-control" name="title" placeholder="title" value="{{ $post['title'] }}">
                    <br>
                    <textarea name="description" class="md-textarea form-control" placeholder="description" rows="3">{{ $post['description'] }}</textarea>
                    <br>
                    <input type="file" name="image">
                    <br><br>
                    <button class="btn btn-primary"> Update Post </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
