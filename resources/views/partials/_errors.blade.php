@if ($errors->any())
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="alert alert-danger mb-0">
                    <ul class="mb-0">
                        <li>{{ $errors->first() }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@elseif (session()->has('message') && session()->has('status'))
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                @php
                    $alert_class = session()->get('status') === 'success' ? 'alert-warning' : 'alert-danger';
                @endphp

                <div class="alert {{ $alert_class }} mb-0">
                    <ul class="mb-0">
                        <li>{{ session()->get('message') }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endif