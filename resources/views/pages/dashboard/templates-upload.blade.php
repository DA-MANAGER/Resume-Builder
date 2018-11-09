@extends('app')

@section('app_content')
    <section id="dashboard" class="dashboard mt-4">
        <div class="container">
            <div class="row">
                @include('partials._dashboard-sidebar')

                <main role="main" class="col-md ml-4 py-3 bg-white shadow-sm">
                    <form name="upload-template" action="{{ route('dashboard.resumes.templates-upload') }}" method="post" enctype="multipart/form-data">
                        @csrf

                        <div class="row align-content-center">
                            <div class="col">
                                <h5 class="font-weight-bold mt-2">Upload Template</h5>
                            </div>

                            <div class="col text-right">
                                <button class="btn btn-outline-primary" type="submit">Upload</button>
                            </div>
                        </div>

                        <div class="row justify-content-center">
                            <div class="col-md-6">
                                <div class="mt-5">
                                    <div class="bg-light p-5">
                                        <div class="custom-file">
                                            <input name="template" id="template" class="custom-file-input" type="file">

                                            <label for="avatar" class="custom-file-label text-left">Select a template zip file.</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </main>
            </div>
        </div>
    </section>
@endsection