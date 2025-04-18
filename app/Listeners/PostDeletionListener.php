<?php

namespace App\Listeners;

use App\Events\PostDeletionEvent;
use App\Notifications\PostDeletionNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class PostDeletionListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(PostDeletionEvent $event): void
    {
        $user = auth()->user();
        $user->notify(new PostDeletionNotification($event->user));
    }
}
