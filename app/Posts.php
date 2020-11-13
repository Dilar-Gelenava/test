<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static where(string $string, $userId)
 * @method static create(array $array)
 */
class Posts extends Model
{
    protected $fillable=[
        "user_id", "title", "description", "image_url",
    ];

}
