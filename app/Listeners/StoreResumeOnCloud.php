<?php

namespace App\Listeners;

use App\Factory\DropboxFactory;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Storage;
use Kunnu\Dropbox\DropboxFile;

/**
 * @package Resume Builder
 * @author  Abhishek Prakash <prakashabhishek6262@gmail.com>
 */
class StoreResumeOnCloud
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $resume = $event->resume;
        $author = $resume->author;
        $cloud  = $author->cloudTokens;

        $dropbox_token = $cloud->where('name', 'dropbox')->pluck('value');

        // Generate and store a copy of resume to the account of dropbox
        // platform if the user has allowed our application.
        if (count($dropbox_token) > 0) {
            $dropbox = (new DropboxFactory)->create($dropbox_token[0]);

            $file_name = $resume->title . ' - ' . time() . '.pdf';

            $path = Storage::getAdapter()->getPathPrefix() . '/resumes/'. $file_name;

            $resume->generatePDF()->save($path);
            $file = new DropboxFile($path);

            $dropbox->upload($file, "/resumes/" . $file_name);
        }
    }
}
