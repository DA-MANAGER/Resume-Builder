<?php

namespace App\Factory;

use App\Store\SessionPersistentDataStore;
use Kunnu\Dropbox\Dropbox;
use Kunnu\Dropbox\DropboxApp;

/**
 * @package Resume Builder
 * @author  Abhishek Prakash <prakashabhishek6262@gmail.com>
 */
class DropboxFactory {
    /**
     * Returns an instance of Auth Helper for Dropbox.
     *
     * @param  string $token
     *
     * @return Dropbox
     */
    public function create($token = null) {
        $app = new DropboxApp(env('DROPBOX_APP_KEY'), env('DROPBOX_APP_SECRET'), $token);

        $config = [
            'persistent_data_store' => new SessionPersistentDataStore(),
            'random_string_generator' => 'openssl',
        ];

        return new Dropbox($app, $config);
    }
}
