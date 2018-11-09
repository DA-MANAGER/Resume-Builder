@extends('app')

@section('app_content')
    <section id="select-plans">
        <div class="container py-5">
            <div class="row">
                <div class="col-md">
                    <h2 class="h3 font-weight-bold mb-5" style="line-height: 1.65;">Hi,<br>Sign up now for <span class="text-warning">UNLIMITED</span> access to all pro features!</h2>

                    <div class="card-deck">
                        @if (! empty($resume_id))
                            <div class="card border-0 shadow rounded-0">
                                <div class="card-body">
                                    <img class="img-fluid mb-3" src="{{ $resume_preview }}">

                                    <a class="font-weight-bold" href="{{ route('resumes.single', ['resume_id' => $resume_id]) }}">
                                        <i class="fa-angle-left mr-2"></i>Edit your resume
                                    </a>
                                </div>
                            </div>
                        @endif

                        <div class="card pricing-card text-center">
                            <div class="card-header">
                                <h3 class="display-2">
                                    <span class="currency">{{ $currencyStore->getLocalCurrencyCode() }}</span>{{ $currencyStore->convertToLocalCurrency(1000) }}
                                </h3>
                            </div>

                            <div class="card-block pb-5 px-5">
                                <h4 class="card-title font-weight-bold">One-Time Download</h4>

                                @include('partials._payment-form', [
                                    'amount' => $currencyStore->convertToLocalCurrency(1000),
                                    'currency' => $currencyStore->getLocalCurrencyCode(),
                                    'description' => 'One-Time Download',
                                    'payment_plan' => null,
                                ])
                            </div>
                        </div>

                        @if (Auth::check())
                            <div class="card pricing-card text-center">
                                <div class="card-header">
                                    <h3 class="display-2">
                                        <span class="currency">{{ $currencyStore->getLocalCurrencyCode() }}</span>{{ $currencyStore->convertToLocalCurrency($plan->amount) }}<span class="period">/{{ $plan->interval }}</span>
                                    </h3>
                                </div>

                                <div class="card-block pb-5 px-5">
                                    <h4 class="card-title font-weight-bold">{{ $plan->name }}</h4>

                                    <ul class="list-group">
                                        <li class="list-group-item"><span class="font-weight-bold text-primary">Unlimited</span> number of Resume</li>
                                        <li class="list-group-item"><span class="font-weight-bold text-primary">Unlimited</span> downloads to PDF</li>
                                        <li class="list-group-item">Ideal for serious <span class="font-weight-bold text-primary">job seekers!</span></li>
                                    </ul>

                                    @include('partials._payment-form', [
                                        'amount' => $currencyStore->convertToLocalCurrency($plan->amount),
                                        'currency' => $currencyStore->getLocalCurrencyCode(),
                                        'description' => $plan->name,
                                        'payment_plan' => $plan->id,
                                    ])
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="pro-membership-features" class="bg-warning">
        <div class="container text-center py-5">
            <h2 class="font-weight-bold">Pro Membership Features</h2>

            <div class="row justify-content-center mt-5">
                <div class="col-md">
                    <span class="d-block mb-4">
                        <img src="{{ asset('images/adobe.png') }}">
                    </span>

                    <h5 class="font-weight-bold">Instant Downloads</h5>
                    <p>The Resumes can be downloaded with just one-click.</p>
                </div>

                <div class="col-md">
                    <span class="d-block mb-4">
                        <img src="{{ asset('images/unlimited.png') }}">
                    </span>

                    <h5 class="font-weight-bold">Unlimited Resume Variations</h5>
                    <p>Maximize your job search with customized documents.</p>
                </div>
                
                <div class="col-md">
                    <span class="d-block mb-4">
                        <img src="{{ asset('images/templates.png') }}">
                    </span>

                    <h5 class="font-weight-bold">Multiple Templates</h5>
                    <p>Comes with many templates to choose from.</p>
                </div>
            </div>

            <div class="row justify-content-center mt-5">
                <div class="col-md-4">
                    <span class="d-block mb-4">
                        <img src="{{ asset('images/light-bulb.png') }}">
                    </span>

                    <h5 class="font-weight-bold">Job Articles</h5>
                    <p>Browse hundreds of articles, how-to guides and pro tips.</p>
                </div>

                <div class="col-md-4">
                    <span class="d-block mb-4">
                        <img src="{{ asset('images/support.png') }}">
                    </span>

                    <h5 class="font-weight-bold">Resume Critique and More</h5>
                    <p>Get 1 on 1 help from an experienced career consultant.</p>
                </div>
            </div>
        </div>
    </section>
@endsection
