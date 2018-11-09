<?php

namespace App\Exceptions;

use Exception;

/**
 * @package Resume Builder
 * @author  Abhishek Prakash <prakashabhishek6262@gmail.com>
 */
class NoPermissionException extends Exception
{
    /**
     * Render the exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request
     * 
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {
        return response()->view('errors.no-permission', [
            'message' => $this->getMessage()
        ]);
    }
}
