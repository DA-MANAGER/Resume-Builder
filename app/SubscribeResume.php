<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @package Resume Builder
 * @author  Abhishek Prakash <prakashabhishek6262@gmail.com>
 */
class SubscribeResume extends Model
{
    /**
     * The table name to assign to the model.
     *
     * @var string
     */
    protected $table = 'subscribe_resume';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'resume_id',
        'subscription_id'
    ];

    /**
     * Defines a relationship between subscriptions and their associated
     * resumes.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function subscription() {
        return $this->belongsTo(Subscription::class, 'subscription_id');
    }
}
