@php
    $metadata = [
        [
            'metaname'  => 'user_id',
            'metavalue' => $user->id
        ]
    ];

    if (! is_null($resume_id)) {
        array_push($metadata, [
            'metaname'  => 'resume_id',
            'metavalue' => $resume_id
        ]);
    }
@endphp

<form method="POST" action="{{ route('payments.pay') }}" id="paymentForm">
    {{ csrf_field() }}

    <input type="hidden" name="payment_method" value="both"/>

    <input type="hidden" name="amount" value="{{ $amount }}"/>
    <input type="hidden" name="currency" value="{{ $currency }}"/>
    <input type="hidden" name="description" value="{{ $description }}"/>
    <input type="hidden" name="paymentplan" value="{{ $payment_plan }}"/>

    <input type="hidden" name="title" value="{{ config('rave.title') }}"/>
    <input type="hidden" name="logo" value="{{ config('rave.logo') }}"/>
    <input type="hidden" name="country" value="NG"/>
    <input type="hidden" name="email" value="{{ $user->email }}"/>
    <input type="hidden" name="metadata" value="{{ json_encode($metadata) }}">
    <input type="hidden" name="ref" value="{{ $txref }}" />

    <input type="hidden" name="pay_button_text" value="Complete Payment"/>
    <input class="btn btn-gradient mt-4" type="submit" value="Choose Plan"/>
</form>
