<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static where(string $string, array|string|null $post_id)
 */
class Likes extends Model
{
    public $timestamps = false;

    protected $fillable=[
        "user_id", "post_id", "is_like",
    ];

}
