<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @package Resume Builder
 * @author  Abhishek Prakash <prakashabhishek6262@gmail.com>
 */
class ResumeToken extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'resume_id',
        'key',
    ];
}
