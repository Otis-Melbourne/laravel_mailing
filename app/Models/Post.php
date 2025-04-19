<?php

namespace App\Models;

use App\Jobs\PostCreationJob;
use App\Mail\PostCreation;
use App\Mail\PostUpdating;
use Illuminate\Support\Facades\Mail;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'name',
        'user_id',
    ];

    protected static function booted(): void
    {
        static::created(function (Post $post) {
            Mail::to(auth()->user()->email)->queue(new PostCreation($post));
        });

        static::updated(function (Post $post) {
            Mail::to(auth()->user()->email)->send(new PostUpdating($post));
        });
    }
}
