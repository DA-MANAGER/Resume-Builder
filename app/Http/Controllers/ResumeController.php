<?php

namespace App\Http\Controllers;

use App\Constants\ResumePermissionError;
use App\Exceptions\NoPermissionException;
use App\Events\ResumeCreated;
use App\Events\ResumeUpdated;
use App\Http\Controllers\Auth\RegisterController;
use App\Resume;
use App\User;
use \Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDF;

/**
 * @package Resume Builder
 * @author  Abhishek Prakash <prakashabhishek6262@gmail.com>
 */
class ResumeController extends Controller
{
    /**
     * Deletes the resume with supplied resume id.
     *
     * @param  int $resume_id
     *
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws NoPermissionException
     */
    public function deleteResume($resume_id) {
        $resume = Resume::with('author')->findOrFail($resume_id);
        $author = $resume->author;

        if (! Auth::check()) {
            throw new NoPermissionException( ResumePermissionError::DELETE );
        }

        $user = Auth::user();

        // To delete a resume either the user should be its owner or they
        // should hold the super user privilege.
        if (! $user->hasPermissionTo('delete resumes')) {
            throw new NoPermissionException( ResumePermissionError::DELETE );
        } elseif ((int) $user->id !== (int) $author->id) {
            if (! $user->hasAnyRole(['administrator', 'moderator'])) {
                throw new NoPermissionException( ResumePermissionError::DELETE );
            }
        }

        $resume->delete();
        return redirect()->route('dashboard.resumes', [
                'username' => $author->username
            ])->with([
                'message' => 'The resume was successfully deleted.',
                'status'  => 'success'
            ]);
    }

    /**
     * Generates a downloadable format of the resume..
     *
     * @param  Request $request
     * @param  int $resume_id
     *
     * @return PDF
     *
     * @throws NoPermissionException
     */
    public function downloadResume(Request $request, $resume_id) {
        $resume = Resume::with("author")->findOrFail($resume_id);
        $author = $resume->author;

        // Redirect the user to pay to download the resume if they don't
        // have any active subscription.
        if (! $author->isSubscribed()) {
            return redirect()->route('payments.plans', ['resume_id' => $resume->id]);
        }

        // We'll use the new template for the resume if any supplied.
        $template = $request->has('template') ?
                        $request->input('template') : $resume->template;

        // To download a resume either the user should be its owner or
        // they should hold the super user privilege.
        if (Auth::check()) {
            $user = Auth::user();

            if ((int) $user->id !== (int) $author->id) {
                if (! $user->hasAnyRole(['administrator', 'moderator'])) {
                    throw new NoPermissionException( ResumePermissionError::VIEW );
                }
            } else {
                if (! $author->attachResumeToSubscription($resume)) {
                    return redirect()->route('resumes.single', [
                            'resume_id' => $resume->id
                        ])
                        ->with([
                            'message' => 'Sorry! something went wrong. Please try again later.',
                            'status'  => 'failed'
                        ]);
                }
            }
        } else {
            if (! $resume->validateToken()) {
                throw new NoPermissionException( ResumePermissionError::VIEW );
            }

            if (! $author->attachResumeToSubscription($resume)) {
                return redirect()->route('resumes.single', [
                        'resume_id' => $resume->id
                    ])
                    ->with([
                        'message' => 'Sorry! something went wrong. Please try again later.',
                        'status'  => 'failed'
                    ]);
            }
        }

        $pdf_name = sha1(Carbon::now()) . '.pdf';
        $data     = json_decode($resume->data);

        return $resume->generatePDF([
                'author'   => $author,
                'data'     => $data,
                'template' => $template
            ])
                ->download($pdf_name);
    }

    /**
     * Generates a clone of the resume.
     *
     * @param  int $resume_id
     *
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws NoPermissionException
     */
    public function duplicateResume($resume_id) {
        $resume = Resume::with('author')->findOrFail($resume_id);
        $author = $resume->author;

        if (! Auth::check()) {
            throw new NoPermissionException( ResumePermissionError::CREATE );
        }

        $user = Auth::user();

        // To duplicate a resume either the user should be its owner or
        // they should hold the super user privilege.
        if (! $user->hasPermissionTo('create resumes')) {
            throw new NoPermissionException( ResumePermissionError::CREATE );
        } elseif ((int) $user->id !== (int) $author->id) {
            if (! $user->hasAnyRole(['administrator', 'moderator'])) {
                throw new NoPermissionException( ResumePermissionError::CREATE );
            }
        }

        $new_resume = $resume->replicate();
        $new_resume->save();

        // Fire an event so the action of storing the resume on cloud or
        // mailing can take place.
        event(new ResumeCreated($new_resume));

        return redirect()->route('resumes.single', [
                'resume_id' => $new_resume->id
            ])->with([
                'message' => 'The resume was successfully duplicated.',
                'status'  => 'success'
            ]);
    }

    /**
     * Displays all the resumes.
     *
     * @param  string $username
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     *
     * @throws NoPermissionException
     */
    public function showAllResumes($username = null) {
        $resumes = new Resume;
        $user    = Auth::user();
        $profile = $user;

        $is_super_user = $user->hasAnyRole(['administrator', 'moderator']);

        // We'll determine whether resumes are being requested of a particular
        // user or all and then display the resumes accordingly.
        if (! empty($username)) {
            $author = User::where('username', $username)->firstOrFail();

            // Restrict the user to access the resumes of other users if
            // they are not the users with super privileges.
            if ((int) $user->id !== (int) $author->id && ! $is_super_user) {
                throw new NoPermissionException( ResumePermissionError::VIEW );
            }

            $profile = $author;
            $resumes = $resumes->with('author')->where('author_id', $author->id);
        } else {
            if (! $is_super_user) {
                return redirect()->route('dashboard.resumes', ['username' => $user->username]);
            }

            $resumes = $resumes->with('author');
        }

        $resumes = $resumes->paginate();

        return view('pages.dashboard.resumes', [
                'profile' => $profile,
                'resumes' => $resumes
            ]);
    }

    /**
     * Displays the singular resume.
     *
     * @param  int $resume_id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     *
     * @throws NoPermissionException
     */
    public function showResume($resume_id) {
        $resume           = Resume::with(['author', 'token'])->findOrFail($resume_id);
        $author           = $resume->author;
        $user             = null;
        $form_action_url  = null;
        $form_method      = "POST";

        // To access a resume either the user should be its owner or they
        // should hold the super user privilege.
        if (Auth::check()) {
            $user = Auth::user();

            if ((int) $user->id !== (int) $author->id) {
                if (! $user->hasAnyRole(['administrator', 'moderator'])) {
                    throw new NoPermissionException( ResumePermissionError::VIEW );
                }
            }

            $form_action_url = route('resumes.update', ['resume_id' => $resume->id]);
            $form_method     = "PUT";
        } else{
            if (! $resume->validateToken()) {
                throw new NoPermissionException( ResumePermissionError::VIEW );
            }

            $form_action_url = route('resumes.download', ['resume_id' => $resume->id]);
        }

        return view('pages.resume-form', [
                'author'          => $author,
                'created_at'      => $resume->created_at->toDateTimeString(),
                'data'            => $resume->data,
                'form_action_url' => $form_action_url,
                'form_method'     => $form_method,
                'resume_id'       => $resume->id,
                'template'        => $resume->template,
                'title'           => $resume->title,
                'updated_at'      => $resume->updated_at->toDateTimeString(),
                'user'            => $user
            ]);
    }

    /**
     * Displays a form to create a new resume.
     *
     * @param  Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     *
     * @throws NoPermissionException
     */
    public function showResumeForm(Request $request) {
        $author = null;
        $user   = null;

        // Restrict the authenticated user from creating a new resume if
        // he doesn't hold the permission.
        if (Auth::check()) {
            $author = Auth::user();
            $user   = $author;

            if (! $user->hasPermissionTo('create resumes')) {
                throw new NoPermissionException( ResumePermissionError::CREATE );
            }

            // Next, we'll check whether the user is a person with super
            // privileges and is trying to create a resume for some other user.
            // And if so, then we'll change author of the resume.
            if ($user->hasAnyRole(['administrator', 'moderator'])) {
                if ($request->has('author_id')) {
                    $author = User::findOrFail($request->input('author_id'));
                }
            }
        }

        $templates = Resume::getTemplates();

        return view('pages.resume-form', [
                'author'          => $author,
                'form_action_url' => route('resumes.store'),
                'form_method'     => 'POST',
                'template'        => $templates[0]['name'],
                'title'           => 'New Resume',
                'user'            => $user
            ]);
    }

    /**
     * Displays a page to start downloading the resume.
     * 
     * @param  int $resume_id
     * 
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     */
    public function showStartingDownloadPage($resume_id) {
        return view('pages.resume-starting-download', ['resume_id' => $resume_id]);
    }

    /**
     * Stores the new resume into the database.
     *
     * @param  Request $request
     * @param  bool $redirect
     *
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws NoPermissionException
     */
    public function storeResume(Request $request, bool $redirect = true) {
        $registration = (bool) $request->input('registration');

        if ($registration === true) {
            $request->validate([
                'author_id'          => 'exists:users,id',
                'data'               => 'required',
                'template'           => 'required|string',
                'title'              => 'required|string',
                'registration_email' => 'required|email|unique:users,email',
                'registration_name'  => 'required|string',
                'registration_pass'  => 'required|string|min:6|max:16',
            ]);
        } else {
            $request->validate([
                'author_id' => 'exists:users,id',
                'data'      => 'required',
                'template'  => 'required|string',
                'title'     => 'required|string',
            ]);
        }

        // Restrict the authenticated user from creating a new resume if
        // he doesn't hold the permission.
        if (Auth::check()) {
            $author = Auth::user();
            $user   = $author;

            if (! $user->hasPermissionTo('create resumes')) {
                throw new NoPermissionException( ResumePermissionError::CREATE );
            }

            // Next, we'll check whether the user is a person with super
            // privileges and is trying to create a resume for some other user.
            // And if so, then we'll change author of the resume.
            if ($user->hasAnyRole(['administrator', 'moderator'])) {
                if ($request->has('author_id')) {
                    $author = User::findOrFail($request->input('author_id'));
                }
            }
        } else {
            // We can create a new random user for the resume to attach as
            // an author if the user is not registering for the
            // application. Otherwise we will simply register the user
            // with their supplied details and authenticate him in the
            // application for the ease of them.
            if ($registration === true) {
                $password = $request->input('registration_pass');

                $author = app(RegisterController::class)->create([
                    'email'    => $request->input('registration_email'),
                    'name'     => $request->input('registration_name'),
                    'password' => $password
                ]);

                Auth::attempt([
                    'username' => $author->username,
                    'password' => $password
                ]);
            } else {
                $author = User::createRandomUser();
            }
        }

        $resume = $author->resumes()->create([
            'data'     => $request->input('data'),
            'template' => $request->input('template'),
            'title'    => $request->input('title')
        ]);

        // Finally, if the user is not authenticated then we need to
        // generate an access token for the user so he can access the
        // resume anytime in the same browser until the cookies are
        // cleared.
        if (! Auth::check()) {
            $resume->generateToken();
        }

        // Fire an event so the action of storing the resume on cloud or
        // mailing can take place.
        event(new ResumeCreated($resume));

        if ($redirect === false) {
            return $resume;
        }

        return redirect()->route('resumes.single', [
                'resume_id' => $resume->id
            ])
            ->with([
                'message' => 'The resume was successfully created.',
                'status'  => 'success'
            ]);
    }

    /**
     * Updates the resume with new supplied details.
     *
     * @param  Request $request
     * @param  int $resume_id
     *
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws NoPermissionException
     */
    public function updateResume(Request $request, $resume_id) {
        $resume = Resume::with(['author', 'token'])->findOrFail($resume_id);
        $author = $resume->author;

        if (! Auth::check()) {
            throw new NoPermissionException( ResumePermissionError::UPDATE );
        }

        $props = $request->validate([
            'author_id' => 'exists:users,id',
            'data'      => 'required',
            'title'     => 'required|string',
            'template'  => 'required|string',
        ]);

        $user = Auth::user();

        // To update a resume either the user should be its owner or
        // they should hold the super user privilege.
        if (! $user->hasPermissionTo('edit resumes')) {
            throw new NoPermissionException( ResumePermissionError::UPDATE );
        } elseif ((int) $user->id !== (int) $author->id) {
            if (! $user->hasAnyRole(['administrator', 'moderator'])) {
                throw new NoPermissionException( ResumePermissionError::UPDATE );
            }
        }

        // Finally, we're good to populate the resume with the updated
        // details.
        foreach ($props as $key => $value) {
            $resume->{$key} = $value;
        }

        $resume->save();

        // Fire an event so the action of storing the resume on cloud or
        // mailing can take place.
        event(new ResumeUpdated($resume));

        return redirect()->route('resumes.single', [
                'resume_id' => $resume->id
            ])
            ->with([
                'message' => 'The resume was successfully updated.',
                'status'  => 'success'
            ]);
    }
}
