<?php

namespace App\Store;

use Kunnu\Dropbox\Store\PersistentDataStoreInterface;
use Kunnu\Dropbox\Store\SessionPersistentDataStore as DropboxSessionPersistentDataStore;

/**
 * @package Resume Builder
 * @author  Abhishek Prakash <prakashabhishek6262@gmail.com>
 */
class SessionPersistentDataStore extends DropboxSessionPersistentDataStore implements PersistentDataStoreInterface
{
    /**
     * Get a value from the store
     *
     * @param  string $key Data Key
     *
     * @return string|null
     */
    public function get($key)
    {
        if (session()->has($this->prefix . $key)) {
            return session()->get($this->prefix . $key);
        }

        return null;
    }

    /**
     * Set a value in the store
     * 
     * @param string $key   Data Key
     * @param string $value Data Value
     *
     * @return void
     */
    public function set($key, $value)
    {
        session([$this->prefix . $key => $value]);
    }

    /**
     * Clear the key from the store
     *
     * @param $key Data Key
     *
     * @return void
     */
    public function clear($key)
    {
        if (session()->has($this->prefix . $key)) {
            session()->forget($this->prefix . $key);
        }
    }
}