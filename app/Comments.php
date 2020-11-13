<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static where(string $string, array|string|null $post_id)
 */
class Comments extends Model
{
    protected $fillable=[
        "user_id", "post_id", "comment",
    ];
}
