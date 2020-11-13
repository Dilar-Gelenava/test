<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Followers extends Model
{
    public $timestamps = false;

    protected $fillable=[
        "follower_id",
        "following_id",
    ];

}
