@extends('app')

@section('app_content')
    <section id="dashboard" class="dashboard mt-4">
        <div class="container">
            <div class="row">
                @include('partials._dashboard-sidebar')

                <main role="main" class="col-md ml-4 py-3 bg-white shadow-sm">
                    <div class="row align-content-center">
                        <div class="col">
                            <h5 class="font-weight-bold mt-2">Subscriptions</h5>
                        </div>

                        <div class="col text-right">
                            <a class="btn btn-outline-primary" href="{{ route('payments.plans') }}">View Plans</a>
                        </div>
                    </div>

                    <div class="row justify-content-center">
                        <div class="col">
                            <div class="mt-3">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">Plan</th>
                                            <th scope="col">Amount</th>
                                            <th scope="col">Transaction Reference</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Resumes</th>
                                            <th scope="col">Subscribed at</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @if (count($subscriptions))
                                            @foreach($subscriptions as $subscription)
                                                @php
                                                    $data = unserialize($subscription->data);

                                                    $amount = $data['currency'] . ' ' . $subscription->amount;
                                                    $badge_class = "";
                                                    $plan_name = "One-Time";

                                                    switch ($subscription->status) {
                                                        case "active" :
                                                            $badge_class = "badge-success";
                                                            break;
                                                        case "pending":
                                                            $badge_class = "badge-warning";
                                                            break;
                                                        default:
                                                            $badge_class = "badge-danger";
                                                    }

                                                    if (is_object($plans) && property_exists($plans, 'id')) {
                                                        if ((int) $plans->id === (int) $subscription->plan_id) {
                                                            $plan_name = $plans->name;
                                                        }
                                                    }
                                                @endphp

                                                <tr>
                                                    <th scope="row">{{ $plan_name }}</th>
                                                    <td class="font-weight-bold">{{ $amount }}</td>
                                                    <td>{{ $subscription->txref }}</td>
                                                    <td>
                                                        <span class="badge badge-pill {{ $badge_class }}">{{ $subscription->status }}</span>
                                                    </td>
                                                    <td>{{ count($subscription->subscribedResumes) }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($subscription->created_at)->diffForHumans(\Carbon\Carbon::now()) }}</td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td>
                                                    <p class="card-text">Sorry! No subscription found.</p>
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>

                                <div class="text-right">
                                    {{ $subscriptions->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </section>
@endsection