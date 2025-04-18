<?php

namespace App\Providers;

use App\Events\PostDeletionEvent;
use App\Listeners\PostDeletionListener;
use App\Models\Post;
use App\Policies\PostPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Post::class, PostPolicy::class);

        Event::listen(
            PostDeletionEvent::class,
            PostDeletionListener::class,
        );
    }
}
