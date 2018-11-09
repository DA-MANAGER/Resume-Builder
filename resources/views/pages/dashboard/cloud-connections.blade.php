@extends('app')

@section('app_content')
    <section id="dashboard" class="dashboard mt-4">
        <div class="container">
            <div class="row">
                @include('partials._dashboard-sidebar')

                <main role="main" class="col-md ml-4 py-3 bg-white shadow-sm">
                    <div class="row align-content-center">
                        <div class="col">
                            <h5 class="font-weight-bold mt-2">Clouds</h5>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <div class="mt-3">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">S No.</th>
                                            <th scope="col">Name</th>
                                            <th scope="col"></th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <tr>
                                            <th scope="row align-baseline">
                                                <span># 1</span>
                                            </th>

                                            <td class="align-baseline">
                                                <i class="fa-dropbox"></i> Dropbox
                                            </td>

                                            <td class="align-baseline text-right">
                                                @php
                                                    $button_class = "btn-light";
                                                    $button_text  = "Connect";

                                                    if (count($cloud->where('name', 'dropbox')) > 0) {
                                                        $button_class = "btn-danger";
                                                        $button_text  = "Disconnect";
                                                    }
                                                @endphp

                                                <form name="connect-dropbox" action="{{ route('dashboard.cloud.dropbox', ['username' => $profile->username]) }}" method="POST">
                                                    @csrf

                                                    @if (count($cloud->where('name', 'dropbox')) > 0)
                                                        @method('DELETE')
                                                    @endif

                                                    <button type="submit" class="btn btn-sm {{ $button_class }}">{{ $button_text }}</button>
                                                </form>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </section>
@endsection