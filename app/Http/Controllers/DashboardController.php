<?php

namespace App\Http\Controllers;

use App\Constants\ProfilePermissionError;
use App\Constants\ResumePermissionError;
use App\Constants\SubscriptionStatus;
use App\Constants\UserPermissionError;
use App\Exceptions\NoPermissionException;
use App\Resume;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Image;
use Rave;
use Spatie\Permission\Models\Role;

/**
 * @package Resume Builder
 * @author  Abhishek Prakash <prakashabhishek6262@gmail.com>
 */
class DashboardController extends Controller
{
    /**
     * Deletes the user by the supplied username.
     *
     * @param  string $username
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws NoPermissionException
     */
    public function deleteProfile($username) {
        $profile = User::where('username', $username)->firstOrFail();
        $user    = Auth::user();

        // To delete a profile either the user should be its owner or they
        // should hold the super user privilege.
        if (! $user->hasPermissionTo('delete users')) {
            throw new NoPermissionException( ProfilePermissionError::DELETE );
        } elseif ((int) $profile->id !== (int) $user->id) {
            if (! $user->hasAnyRole(['administrator', 'moderator'])) {
                throw new NoPermissionException( ProfilePermissionError::DELETE );
            }
        } else {
            $profile->delete();
            return redirect()->route('resumes.create')->with([
                    'message' => 'The profile was successfully deleted.',
                    'status'  => 'success'
                ]);
        }

        $profile->delete();
        return redirect()->route('dashboard.users')->with([
                'message' => 'The profile was successfully deleted.',
                'status'  => 'success'
            ]);
    }

    /**
     * Deletes the resume template.
     *
     * @param  Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws NoPermissionException
     */
    public function deleteResumeTemplate(Request $request) {
        $request->validate([
            'template' => 'required|string'
        ]);

        $user = Auth::user();

        if (! $user->hasRole('administrator')) {
            throw new NoPermissionException( ResumePermissionError::TEMPLATE );
        }

        $deletion = Resume::deleteTemplate( $request->input('template'));

        if ($deletion) {
            return redirect()->route('dashboard.resumes.templates')->with([
                    'message' => 'The template was successfully deleted.',
                    'status'  => 'success'
                ]);
        }

        return redirect()->route('dashboard.resumes.templates')->with([
                'message' => 'Failed to delete the template.',
                'status'  => 'failed'
            ]);
    }

    /**
     * Hides the resume template for the client.
     *
     * @param  Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws NoPermissionException
     */
    public function ignoreResumeTemplate(Request $request) {
        $request->validate([
            'template' => 'required|string'
        ]);

        $user = Auth::user();

        if (! $user->hasRole('administrator')) {
            throw new NoPermissionException( ResumePermissionError::TEMPLATE );
        }

        if (Resume::ignoreTemplate($request->input('template'))) {
            return redirect()->route('dashboard.resumes.templates')->with([
                    'message' => 'Successfully hidden the template.',
                    'status'  => 'success'
                ]);
        }

        return redirect()->route('dashboard.resumes.templates')->with([
                'message' => 'Failed to hide the template.',
                'status'  => 'failed'
            ]);
    }

    /**
     * Displays the connections made to the cloud to store a copy resume
     * on them.
     *
     * @param  string $username
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     * @throws NoPermissionException
     */
    public function showCloudConnections($username) {
        $profile = User::with('cloudTokens')->where('username', $username)->firstOrFail();
        $user    = Auth::user();

        // To view a profile either the user should be its owner or they
        // should hold the super user privilege.
        if ((int) $profile->id !== (int) $user->id) {
            if (! $user->hasAnyRole(['administrator', 'moderator'])) {
                throw new NoPermissionException( ProfilePermissionError::VIEW );
            }
        }

        return view('pages.dashboard.cloud-connections', [
            'profile' => $profile,
            'cloud'   => $profile->cloudTokens
        ]);
    }

    /**
     * Displays the user profile of the supplied username.
     *
     * @param  string $username
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     * @throws NoPermissionException
     */
    public function showProfile($username) {
        $profile    = User::where('username', $username)->firstOrFail();
        $user       = Auth::user();
        $user_roles = [];

        // To view a profile either the user should be its owner or they
        // should hold the super user privilege.
        if ((int) $profile->id !== (int) $user->id) {
            if (! $user->hasAnyRole(['administrator', 'moderator'])) {
                throw new NoPermissionException( ProfilePermissionError::VIEW );
            } elseif ($user->hasRole('administrator')) {
                $user_roles = Role::all();
            }
        }

        return view('pages.dashboard.profile', [
            'profile'    => $profile,
            'user_roles' => $user_roles
        ]);
    }

    /**
     * Displays the list resume templates available in the application.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     * @throws NoPermissionException
     */
    public function showResumeTemplates() {
        $user             = Auth::user();
        $ignore_templates = Resume::getIgnoredTemplates();
        $templates        = Resume::getTemplates();

        if (! $user->hasRole('administrator')) {
            throw new NoPermissionException( ResumePermissionError::TEMPLATE );
        }

        return view('pages.dashboard.templates', [
            'profile'          => $user,
            'ignore_templates' => $ignore_templates,
            'templates'        => $templates
        ]);
    }

    /**
     * Displays the statistics of the user profile and application.
     *
     * @param  string $username
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     * @throws NoPermissionException
     */
    public function showStatistics($username) {
        $profile = User::with([
                'resumes',
                'subscriptions' => function ($query) {
                    $query->where('status', SubscriptionStatus::ACTIVE)->latest()->first();
                }
            ])
                ->where('username', $username)
                ->firstOrFail();

        $user = Auth::user();

        $super_user = $user->hasAnyRole(['administrator', 'moderator']);

        $payment_plan       = "N/A";
        $total_resume_count = null;
        $total_templates    = null;
        $total_user_count   = null;

        // To view a profile either the user should be its owner or they
        // should hold the super user privilege.
        if ((int) $profile->id !== (int) $user->id) {
            if (! $super_user) {
                throw new NoPermissionException( ProfilePermissionError::VIEW );
            }
        } elseif ($super_user) {
            $total_resume_count = Resume::all()->count();
            $total_user_count   = User::all()->count();

            if ($user->hasRole('administrator')) {
                $total_templates    = count(Resume::getTemplates());
            }
        }

        if (count($profile->subscriptions) > 0) {
            $subscription = $profile->subscriptions[0];

            if (! empty($subscription->plan_id)) {
                $response = Rave::fetchPaymentPlan($subscription->plan_id);
                $plan     = Rave::parsePaymentResponse($response);

                $payment_plan = $plan->name;
            } else {
                $payment_plan = "One-Time";
            }
        }

        return view('pages.dashboard.statistics', [
            'profile' => $profile,
            'payment_plan' => $payment_plan,
            'total_resume_count' => $total_resume_count,
            'total_templates' => $total_templates,
            'total_user_count' => $total_user_count
        ]);
    }

    /**
     * Displays the plan subscriptions for the user.
     *
     * @param  string $username
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     * @throws NoPermissionException
     */
    public function showSubscriptions($username) {
        $profile = User::where('username', $username)->firstOrFail();
        $user    = Auth::user();

        // To view a profile subscriptions the user should be its owner or
        // they should hold the super user privilege.
        if ((int) $profile->id !== (int) $user->id) {
            if (! $user->hasAnyRole(['administrator', 'moderator'])) {
                throw new NoPermissionException( ProfilePermissionError::VIEW );
            }
        }

        $response = Rave::fetchPaymentPlan();
        $plans    = Rave::parsePaymentResponse($response);

        $subscriptions = $profile->subscriptions()->with('subscribedResumes')->latest()->paginate();

        return view('pages.dashboard.subscriptions', [
            'profile'       => $profile,
            'plans'         => $plans,
            'subscriptions' => $subscriptions
        ]);
    }

    /**
     * Displays a form to upload new resume templates in the application.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     * @throws NoPermissionException
     */
    public function showUploadResumeTemplateForm() {
        $user = Auth::user();

        if (! $user->hasAnyRole(['administrator'])) {
            throw new NoPermissionException( ResumePermissionError::TEMPLATE );
        }

        return view('pages.dashboard.templates-upload', [
            'profile' => $user
        ]);
    }

    /**
     * Displays the list of users available in the application.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     * @throws NoPermissionException
     */
    public function showUsers() {
        $profile = Auth::user();
        $users   = User::with('roles')->paginate();

        if (! $profile->hasAnyRole(['administrator', 'moderator'])) {
            throw new NoPermissionException( UserPermissionError::VIEW );
        }

        return view('pages.dashboard.users', [
            'profile' => $profile,
            'users'   => $users
        ]);
    }

    /**
     * Unhides the resume template for the client.
     *
     * @param  Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws NoPermissionException
     */
    public function unignoreResumeTemplate(Request $request) {
        $request->validate([
            'template' => 'required|string'
        ]);

        $user = Auth::user();

        if (! $user->hasRole('administrator')) {
            throw new NoPermissionException( ResumePermissionError::TEMPLATE );
        }

        if (Resume::unignoreTemplate($request->input('template'))) {
            return redirect()->route('dashboard.resumes.templates')->with([
                    'message' => 'Successfully unhidden the template.',
                    'status'  => 'success'
                ]);
        }

        return redirect()->route('dashboard.resumes.templates')->with([
                'message' => 'Failed to unhide the template.',
                'status'  => 'failed'
            ]);
    }

    /**
     * Updates the user profile of the supplied username.
     *
     * @param  Request $request
     * @param  string $username
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws NoPermissionException
     */
    public function updateProfile(Request $request, $username) {
        $profile = User::where('username', $username)->firstOrFail();
        $user    = Auth::user();

        // To update a profile either the user should be its owner or they
        // should hold the super user privilege.
        if ((int) $profile->id !== (int) $user->id) {
            if (! $user->hasAnyRole(['administrator', 'moderator'])) {
                throw new NoPermissionException( ProfilePermissionError::UPDATE );
            }
        }

        $props = $request->validate([
            'avatar'   => 'image',
            'email'    => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($profile->id)
            ],
            'name'     => 'required|string|max:255',
            'password' => 'nullable|string|min:6',
        ]);

        foreach ($props as $key => $value) {
            // We can simply skip the avatar here since it will be taken
            // care of later.
            if ($key === 'avatar') {
                continue;
            } elseif ($key === 'password') {
                if (empty($value)) {
                    continue;
                }

                $value = Hash::make($value);
            }

            $profile->{$key} = $value;
        }

        if ($request->hasFile('avatar')) {
            $file  = $request->file('avatar');
            $path  = $file->hashName('avatars');
            $image = Image::make($file);

            $image->fit(300, 300, function ($constraint) {
                $constraint->aspectRatio();
            });

            Storage::disk('public')->put($path, (string) $image->encode());
            $profile->avatar = Storage::url($path);
        }

        // Assign a new role to the user if an adminstrator is requesting
        // to do so.
        if ($request->has('user-role') && $user->hasRole('administrator')) {
            $profile->syncRoles($request->input('user-role'));
        }

        $profile->save();

        return redirect()->route('dashboard.profile', [
                'username' => $profile->username
            ])
            ->with([
                'message' => 'Successfully updated the profile.',
                'status'  => 'success'
            ]);
    }

    /**
     * Uploads a new resume template in the application.
     *
     * @param  Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws NoPermissionException
     */
    public function uploadResumeTemplate(Request $request) {
        $user = Auth::user();

        if (! $user->hasAnyRole(['administrator'])) {
            throw new NoPermissionException( ResumePermissionError::TEMPLATE );
        }

        $request->validate([
            'template' => 'required|mimetypes:application/zip,application/x-zip-compressed,multipart/x-zip,application/x-compressed'
        ]);

        $zip = new \ZipArchive();
        $res = $zip->open(
            $request->file('template'),
            \ZipArchive::CHECKCONS
        );

        if ($res === true) {
            if ($zip->locateName('index.blade.php') === false) {
                return redirect()->route("dashboard.resumes.templates")->with([
                        'message' => 'The zip file does not contain a valid template.',
                        'status'  => 'failed'
                    ]);
            } elseif ($zip->locateName('thumbnail.jpg') === false) {
                return redirect()->route("dashboard.resumes.templates")->with([
                        'message' => 'The template must contain a thumbnail.jpg file for the preview.',
                        'status'  => 'failed'
                    ]);
            }

            $zip->extractTo(resource_path("views/resumes/test/"));
        }

        return redirect()->route("dashboard.resumes.templates")->with([
                'message' => 'Successfully uploaded the template.',
                'status'  => 'success'
            ]);
    }
}
