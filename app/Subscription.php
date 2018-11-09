<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @package Resume Builder
 * @author  Abhishek Prakash <prakashabhishek6262@gmail.com>
 */
class Subscription extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'amount',
        'data',
        'payment_gateway',
        'plan_id',
        'status',
        'txref',
        'user_id'
    ];

    /**
     * Defines a relationship between subscriptions and their associated
     * resumes.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subscribedResumes() {
        return $this->hasMany(SubscribeResume::class, 'subscription_id');
    }

    /**
     * Defines a relationship between subscriptions and its associated
     * user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
}
