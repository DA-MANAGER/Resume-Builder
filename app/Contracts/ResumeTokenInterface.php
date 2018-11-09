<?php

namespace App\Contracts;

/**
 * @package Resume Builder
 * @author  Abhishek Prakash <prakashabhishek6262@gmail.com>
 */
interface ResumeTokenInterface {
    public function generateToken();
    public function validateToken(): bool;
}
