@extends('app')

@section('app_content')
    <section id="dashboard" class="dashboard mt-4">
        <div class="container">
            <div class="row">
                @include('partials._dashboard-sidebar')

                <main role="main" class="col-md ml-4 py-3 bg-white shadow-sm">
                    <form name="profile" action="{{ route('dashboard.profile', ['username' => $profile->username]) }}" method="post" enctype="multipart/form-data">
                        @csrf

                        <div class="row align-content-center">
                            <div class="col">
                                <h5 class="font-weight-bold mt-2">Profile</h5>
                            </div>

                            <div class="col text-right">
                                <span class="badge badge-pill badge-warning mr-2 text-capitalize">{{ $profile->getRoleNames()[0] }}</span>

                                <button class="btn btn-outline-primary" type="submit">Save</button>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-8">
                                <div class="mt-3">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label class="col-form-label font-weight-bold" for="email-address">Email address</label>
                                        </div>

                                        <div class="col-sm-8">
                                            <input name="email" id="email-address" type="text" class="form-control" placeholder="example@mail.com" value="{{ $profile->email }}">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label class="col-form-label font-weight-bold" for="username">Username</label>
                                        </div>

                                        <div class="col-sm-8">
                                            <input name="username" id="username" type="text" class="form-control disabled" placeholder="john.smith" value="{{ $profile->username }}" disabled>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label class="col-form-label font-weight-bold" for="full-name">Full name</label>
                                        </div>

                                        <div class="col-sm-8">
                                            <input name="name" id="full-name" type="text" class="form-control" placeholder="John Smith" value="{{ $profile->name }}">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label class="col-form-label font-weight-bold" for="new-password">New Password</label>
                                        </div>

                                        <div class="col-sm-8">
                                            <input name="password" id="new-password" type="password" class="form-control" placeholder="New Password">
                                        </div>
                                    </div>

                                    @if (! empty($user_roles))
                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <label class="col-form-label font-weight-bold" for="user-role">Role</label>
                                            </div>

                                            <div class="col-sm-8">
                                                <select name="user-role" id="user-role" class="custom-select d-block w-100">
                                                    <option value="" selected disabled>Choose...</option>
                                                    @foreach ($user_roles as $role)
                                                        @php
                                                            $name = ucfirst($role->name);
                                                        @endphp

                                                        <option value="{{ $role->name }}">{{ $name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mt-3">
                                    <div class="text-center">
                                        @if (! empty($profile->avatar))
                                            <img class="rounded mx-auto d-block" style="width: 140px; height: 140px;" src="{{ $profile->avatar }}">
                                        @else
                                            <img class="rounded mx-auto d-block" style="width: 140px; height: 140px;" src="{{ asset('images/avatar.png' ) }}">
                                        @endif

                                        <div class="input-group mx-auto mt-3" style="max-width: 200px;">
                                            <div class="custom-file">
                                                <input name="avatar" type="file" class="custom-file-input" id="avatar">

                                                <label class="custom-file-label text-left" for="avatar">Select avatar</label>
                                            </div>
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