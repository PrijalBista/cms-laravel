<?php

namespace App\Observers;

use App\Photo;
use Illuminate\Support\Facades\Storage;

class PhotoObserver
{
    /**
     * Handle the photo "created" event.
     *
     * @param  \App\Photo  $photo
     * @return void
     */
    public function created(Photo $photo)
    {
        //
    }

    /**
     * Handle the photo "updated" event.
     *
     * @param  \App\Photo  $photo
     * @return void
     */
    public function updated(Photo $photo)
    {
        //
    }

    /**
     * Handle the photo "deleted" event.
     *
     * @param  \App\Photo  $photo
     * @return void
     */
    public function deleted(Photo $photo)
    {
        Storage::disk('public')->delete($photo->url);
    }

    /**
     * Handle the photo "restored" event.
     *
     * @param  \App\Photo  $photo
     * @return void
     */
    public function restored(Photo $photo)
    {
        //
    }

    /**
     * Handle the photo "force deleted" event.
     *
     * @param  \App\Photo  $photo
     * @return void
     */
    public function forceDeleted(Photo $photo)
    {
        //
    }
}
