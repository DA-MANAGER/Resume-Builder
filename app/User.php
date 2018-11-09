<?php

namespace App;

use App\Constants\SubscriptionStatus;
use App\Http\Controllers\Auth\RegisterController;
use Faker\Factory as Faker;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

/**
 * @package Resume Builder
 * @author  Abhishek Prakash <prakashabhishek6262@gmail.com>
 */
class User extends Authenticatable
{
    use HasRoles, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'avatar',
        'name',
        'email',
        'password',
        'username'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Defines the relationship between the user and their clouds.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cloudTokens()
    {
        return $this->hasMany(CloudToken::class, 'user_id');
    }

    /**
     * Defines the relationship between the user and their resumes.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function resumes()
    {
        return $this->hasMany(Resume::class, 'author_id');
    }

    /**
     * Attaches the resume to the active subscription plan of the user.
     * 
     * @param  Resume $resume
     * 
     * @return bool
     */
    public function attachResumeToSubscription(Resume $resume) : bool {
        $subscription = $this->subscriptions()
            ->where('status', SubscriptionStatus::ACTIVE)
            ->latest()
            ->first();

        if (empty($subscription)) {
            return false;
        }

        $subscription->subscribedResumes()->create([
            'resume_id' => $resume->id
        ]);

        if (empty($subscription->plan_id)) {
            $subscription->status = SubscriptionStatus::ENDED;
            $subscription->save();
        }

        return true;
    }

    /**
     * Defines a relationship between subscriptions and its associated
     * user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, 'user_id');
    }

    /**
     * Determines whether the user is subscribed to any plan.
     *
     * @return bool
     */
    public function isSubscribed(): bool
    {
        $subscription = $this->subscriptions()
            ->where('status', SubscriptionStatus::ACTIVE)
            ->latest()
            ->exists();

        return (bool) $subscription;
    }

    /**
     * Unsubscribe the user from the actively subscribed plan.
     *
     * @return bool
     */
    public function unsubscribe(): bool
    {
        if (! $this->isSubscribed()) {
            return false;
        }

        $subscription = $this->subscriptions()
            ->where('status', SubscriptionStatus::ACTIVE)
            ->latest()
            ->first();

        $subscription->status = SubscriptionStatus::ENDED;
        $subscription->save();

        return true;
    }

    /**
     * Creates a random user.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public static function createRandomUser()
    {
        $faker = Faker::create();
        $username = self::generateUsername();

        return app(RegisterController::class)->create([
            'email' => self::generateEmail(),
            'name' => $username,
            'password' => $faker->sha256,
            'username' => $username,
        ]);
    }

    /**
     * Generates a random email that's truly unique and needed not to
     * be checked for uniqueness back again.
     *
     * @return string
     */
    public static function generateEmail(): string
    {
        $faker = Faker::create();
        $email = $faker->safeEmail;

        $emails = User::pluck('email')->toArray();

        // Return the generated email if it doesn't exist in the
        // Database.
        if (!in_array($email, $emails)) {
            return $email;
        }

        // Generate the mail again if it exists in the emails.
        do {
            $email = $faker->safeEmail;
        } while (in_array($email, $emails));

        return $email;
    }

    /**
     * Generates a random username that's truly unique and needed not to
     * be checked for uniqueness back again.
     *
     * @return string
     */
    public static function generateUsername(): string
    {
        $username = 'user' . rand(0, 10000);

        // List all the usernames that exists in the Database.
        $usernames = User::pluck('username')->toArray();

        // Return the generated username if it doesn't exist in the
        // Database.
        if (!in_array($username, $usernames)) {
            return $username;
        }

        // Suffix random numbers until it becomes unique and return.
        do {
            $username .= rand(0, 9);
        } while (in_array($username, $usernames));

        return $username;
    }
}
