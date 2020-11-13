<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserData extends Model
{
    protected $fillable=[
        "user_id",
        "first_name",
        "last_name",
        "bio",
        "birthday",
        "address",
        "followers",
        "following",
        "profile_picture_url",
    ];
}
