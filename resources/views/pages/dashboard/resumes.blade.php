@extends('app')

@section('app_content')
    <section id="dashboard" class="dashboard mt-4">
        <div class="container">
            <div class="row">
                @include('partials._dashboard-sidebar')

                <main role="main" class="col-md ml-4 py-3 bg-white shadow-sm">
                    <div class="row align-content-center">
                        <div class="col">
                            <h5 class="font-weight-bold mt-2">Resumes</h5>
                        </div>

                        <div class="col text-right">
                            <a class="btn btn-outline-primary" href="{{ route('resumes.create') }}">Create new</a>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <div class="mt-3">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">Title</th>
                                            <th scope="col">Template</th>
                                            <th scope="col">Author</th>
                                            <th scope="col">Last updated</th>
                                            <th scope="col"></th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @if (count($resumes))
                                            @foreach($resumes as $resume)
                                                <tr>
                                                    <th scope="row">
                                                        <a href="{{ route('resumes.single', ['resume_id' => $resume->id]) }}">{{ $resume->title }}</a>
                                                    </th>
                                                    <td>
                                                        <span class="badge badge-pill badge-warning">{{ $resume->template }}</span>
                                                    </td>
                                                    <td>{{ $resume->author->name }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($resume->updated_at)->diffForHumans(\Carbon\Carbon::now()) }}</td>
                                                    <td class="text-right">
                                                        <form class="d-inline" name="download-resume" action="{{ route('resumes.download', ['resume_id' => $resume->id]) }}" method="post">
                                                            @csrf

                                                            <button type="submit" class="btn btn-sm btn-light">
                                                                <i class="fa-download"></i>
                                                            </button>
                                                        </form>

                                                        <form class="d-inline" name="duplicate-resume" action="{{ route('resumes.duplicate', ['resume_id' => $resume->id]) }}" method="post">
                                                            @csrf

                                                            <button type="submit" class="btn btn-sm btn-light">
                                                                <i class="fa-copy"></i>
                                                            </button>
                                                        </form>

                                                        <form class="d-inline" name="delete-resume" action="{{ route('resumes.destroy', ['resume_id' => $resume->id]) }}" method="post">
                                                            @csrf
                                                            @method('DELETE')

                                                            <button type="submit" class="btn btn-sm btn-danger">
                                                                <i class="fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td>
                                                    <p class="card-text">Sorry! No resume found.</p>
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>

                                <div class="text-right">
                                    {{ $resumes->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </section>
@endsection