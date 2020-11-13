@extends('layouts.app')

@section('content')
    <link rel="stylesheet" type="text/css" href="../../css/main.css">


        <div class="container" style="text-align: center; width: 450px; background-color: #646464; border-radius: 15px;">

            <h1>{{ $user_name }}</h1>
            <a href="../{{ $profile_picture_url }}">
                <img style="border-radius: 15px; margin-top: 5px;"
                     src="../{{ $profile_picture_url }}"
                     width="400px" alt="{{ $user_data->first_name }}">
            </a>

            <p> {{ $user_data->bio }} </p>
            <a href="/posts/{{ $user_id }}" class="btn btn-info" style="width: 200px;">
                View {{ $user_name }}'s Posts
            </a>
            @if($user_id != auth()->id())
                <form action="{{ route('follow') }}" method="POST">
                    @csrf
                    <input type="hidden" name="following_id" value="{{ $user_id }}">
                    @if($following)
                        <button class="btn btn-warning" style="margin: 10px;">
                            Unfollow {{ $user_name }}
                        </button>
                    @else
                        <button class="btn btn-success" style="margin: 10px;">
                            Follow {{ $user_name }}
                        </button>
                    @endif
                </form>
            @endif
            <button id="showFollowersButton" onclick="showFollowers()" class="btn btn-info" style="margin: 10px;">
                Show Followers
            </button>
            <br>
            <div id="followersList" style="display: none; background-color: #4b4b4b; border-radius: 15px; padding: 10px;">
                <div style="display: inline-block; background-color: #323232; border-radius: 15px; padding: 5px; vertical-align: top">
                    <h4>Followers</h4>
                    @foreach($user_followers as $f)
                        <div style="background-color: #141414; padding: 5px; border-radius: 10px; text-align: center; margin: 5px;">
                            <span>{{ $loop->index + 1 }}</span>
                            <a href="/{{ $f->follower_id }}/{{ $f->name }}" target="_blank">
                                {{ $f->name }}
                            </a>
                        </div>
                    @endforeach
                </div>
                <div style="display: inline-block; background-color: #323232; border-radius: 15px; padding: 5px; vertical-align: top">
                    <h4>Following</h4>
                    @foreach($user_follows as $f)
                        <div style="background-color: #141414; padding: 5px; border-radius: 10px; text-align: center; margin: 5px;">
                            <span>{{ $loop->index + 1 }}</span>
                            <a href="/{{ $f->following_id }}/{{ $f->name }}" target="_blank">
                                {{ $f->name }}
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>


    <div class="container" style="background-color: #282828; border-radius: 15px; margin-top: 15px;">
        <table class="table">
            <tr>
                <th> <p>First Name</p> </th>
                <th> <p>Last Name</p> </th>
                <th> <p>Birthday</p> </th>
                <th> <p>Address</p> </th>
                <th> <p>Followers</p> </th>
                <th> <p>Following</p> </th>
            </tr>
            <tr>
                <td> <p>{{ $user_data->first_name }}</p> </td>
                <td> <p>{{ $user_data->last_name }}</p> </td>
                <td> <p>{{ $user_data->birthday }}</p> </td>
                <td> <p>{{ $user_data->address }}</p> </td>
                <td> <p>{{ $user_data->followers }}</p> </td>
                <td> <p>{{ $user_data->following }}</p> </td>
            </tr>
        </table>
    </div>
    @if ($user_id == auth()->id())
        <div class="container" style="width: 300px; background-color: #646464; border-radius: 15px; padding: 10px;">
            <form action="{{ route("storeUserData") }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="userId" value="{{ $user_id }}">
                @isset($user_data)
                    <input type="text" class="form-control" placeholder="First Name" name="firstName" value="{{ $user_data->first_name }}">
                    <br>
                    <input type="text" class="form-control" placeholder="Last Name" name="lastName" value="{{ $user_data->last_name }}">
                    <br>
                    <textarea class="form-control" name="bio" placeholder="Bio">{{ $user_data->bio }}</textarea>
                    <br>
                    <input type="date" class="form-control" placeholder="Birthday" name="birthday" value="{{ $user_data->birthday }}">
                    <br>
                    <input type="text" class="form-control" placeholder="Address" name="address" value="{{ $user_data->address }}">
                    <br>
                    <input type="file" name="image" style="width: auto;">
                @else
                    <input type="text" class="form-control" placeholder="First Name" name="firstName">
                    <br>
                    <input type="text" class="form-control" placeholder="Last Name" name="lastName">
                    <br>
                    <textarea class="form-control" name="bio" placeholder="Bio"></textarea>
                    <br>
                    <input type="date" class="form-control" placeholder="Birthday" name="birthday">
                    <br>
                    <input type="text" class="form-control" placeholder="Address" name="address">
                    <br>
                    <input type="file" name="image" style="width: auto;">
                @endisset
                <br><br>
                <button class="btn btn-primary" style="width: 200px;"> Update </button>
            </form>
        </div>
    @endif

    </div>
@endsection

<script>

    let followersAreVisible = false;

    function showFollowers() {
        if (followersAreVisible) {
            document.getElementById('followersList').style.display = 'none';
            document.getElementById('showFollowersButton').innerHTML = 'Hide Followers';
            followersAreVisible = false;
        } else {
            document.getElementById('followersList').style.display = 'inline-block';
            document.getElementById('showFollowersButton').innerHTML = 'Show Followers';
            followersAreVisible = true;
        }

    }


</script>
