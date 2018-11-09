<?php

namespace App\Http\Controllers;

use App\Constants\SubscriptionStatus;
use App\Resume;
use App\Store\LocalizeCurrency;
use App\Subscription;
use App\User;
use Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDF;
use Rave;

/**
 * @package Resume Builder
 * @author  Abhishek Prakash <prakashabhishek6262@gmail.com>
 */
class RaveController extends Controller
{
    /**
     * Initialize Rave payment process.
     *
     * @return void
     */
    public function initialize()
    {
        $data = request()->toArray();
        $meta = json_decode($data['metadata']);

        $user_meta = array_values(
            array_where($meta, function ($item) {
                return $item->metaname === 'user_id';
            })
        );

        $user_id = $user_meta[0]->metavalue;
        $user = User::findOrFail($user_id);

        $customer_data = serialize([
            "country" => $data['country'],
            "currency" => $data['currency'],
            "customer_email" => $data['email'],
            "payment_method" => $data['payment_method'],
        ]);

        $user->subscriptions()->create([
            'amount' => $data['amount'],
            'data' => $customer_data,
            'plan_id' => $data['paymentplan'],
            'payment_gateway' => 'rave',
            'status' => SubscriptionStatus::PENDING,
            'txref' => $data['ref']
        ]);

        $resume_meta = array_values(
            array_where($meta, function ($item) {
                return $item->metaname === 'resume_id';
            })
        );

        // We'll attach the resume to the selected plan if the user has
        // submitted the resume.
        if (!empty($resume_meta)) {
            $resume_id = $resume_meta[0]->metavalue;
            session()->flash('rave_resume_id', $resume_id);
        }

        Rave::initialize(
            route('payments.callback')
        );
    }

    /**
     * Obtain Rave callback information.
     *
     * @return PDF|\Illuminate\Http\RedirectResponse|null
     */
    public function callback()
    {
        $resume_id = session('rave_resume_id');
        $txref = request()->input('txref');

        Rave::verifyTransaction($txref);
        $subscription = Subscription::with('user')->where('txref', $txref)->firstOrFail();
        $user = $subscription->user;

        $response = request()->toArray();
        $response = json_decode($response['resp']);

        if (property_exists($response, 'cancelled') && $response->cancelled) {
            $subscription->status = SubscriptionStatus::FAILED;
            $subscription->save();

            if (!empty($resume_id)) {
                return redirect()->route('resumes.single', [
                    'resume_id' => $resume_id
                ])
                    ->with([
                        'message' => 'Failed to subscribe to the subscription plan.',
                        'status' => 'failed'
                    ]);
            }

            return redirect()->route('dashboard.statistics', [
                'username' => $user->username
            ])
                ->with([
                    'message' => 'Failed to subscribe to the subscription plan.',
                    'status' => 'failed'
                ]);
        }


        $status = $response->data->data->status;

        if ($status !== 'successful') {
            $subscription->status = SubscriptionStatus::FAILED;
            $subscription->save();

            if (!empty($resume_id)) {
                return redirect()->route('resumes.single', [
                    'resume_id' => $resume_id
                ])
                    ->with([
                        'message' => 'Failed to subscribe to the subscription plan.',
                        'status' => 'failed'
                    ]);
            }

            return redirect()->route('dashboard.statistics', [
                'username' => $user->username
            ])
                ->with([
                    'message' => 'Failed to subscribe to the subscription plan.',
                    'status' => 'failed'
                ]);
        }

        $subscription->status = SubscriptionStatus::ACTIVE;
        $subscription->save();

        if (!empty($resume_id)) {
            return redirect()->route('resumes.starting-download', ['resume_id' => $resume_id]);
        }

        return redirect()->route('dashboard.statistics', [
            'username' => $user->username
        ])
            ->with([
                'message' => 'Successfully subscribed to the subscription plan.',
                'status' => 'success'
            ]);
    }

    /**
     * Displays the list of plans to subscribe.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     */
    public function showPlans(Request $request)
    {
        $user = null;
        $resume_id = null;
        $resume_preview = null;

        if ($request->has('resume_id')) {
            $resume_id = $request->input('resume_id');

            $resume = Resume::with('author')->findOrFail($resume_id);
            $author = $resume->author;

            if (Auth::check()) {
                $user = Auth::user();

                if ((int)$user->id !== (int)$author->id) {
                    if (!$user->hasAnyRole(['administrator', 'moderator'])) {
                        return redirect()->route('resumes.create')
                            ->with([
                                'message' => 'Sorry! You are not allowed to download this resume.',
                                'status' => 'failed'
                            ]);
                    }
                }
            } elseif (!$resume->validateToken()) {
                return redirect()->route('resumes.create')
                    ->with([
                        'message' => 'Sorry! You are not allowed to download this resume.',
                        'status' => 'failed'
                    ]);
            }

            if ($author->isSubscribed()) {
                return redirect()->back()->with([
                    'message' => 'You are already subscribed to a subscription plan.',
                    'status' => 'success'
                ]);
            }

            $user = $author;
            $resume_preview = $resume->generatePreview();
        } else {
            if (!Auth::check()) {
                return redirect()->route('resumes.create')
                    ->with([
                        'message' => 'Sorry! Either create a resume or login to the application to continue.',
                        'status' => 'failed'
                    ]);
            } else {
                $user = Auth::user();

                if ($user->isSubscribed()) {
                    return redirect()->back()->with([
                        'message' => 'You are already subscribed to a subscription plan.',
                        'status' => 'success'
                    ]);
                }
            }
        }

        $plan_id = config('rave.payment_plan');
        $response = Rave::fetchPaymentPlan($plan_id);
        $plan = Rave::parsePaymentResponse($response);
        
        $txref = Config::get('rave.prefix');
        $txref = $txref . '_';

        return view('pages.payment-plans', [
            'currencyStore' => new LocalizeCurrency,
            'plan' => $plan,
            'resume_id' => $resume_id,
            'resume_preview' => $resume_preview,
            'user' => $user,
            'txref' => uniqid($txref)
        ]);
    }
}
