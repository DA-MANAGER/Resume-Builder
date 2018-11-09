@extends('app')

@section('app_content')
    <section class="resume-starting-download">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm mt-5">
                        <div class="card-body">
                            <p class="card-text lead">Your resume will automatically start downloading in few seconds...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <form id="download-resume" class="d-inline" name="download-resume" action="{{ route('resumes.download', ['resume_id' => $resume_id]) }}" method="post" hidden>
            @csrf
        </form>
    </section>
@endsection

@push('app_footer')
    <script>
        {{--
            We need to wait for some seconds before we can actually start
            downloading the resume to prevent status flickering to ACTIVE
            of the user subscription.
        --}}
        setTimeout(function () {
            document.getElementById("download-resume").submit();
        }, 4000);
    </script>
@endpush