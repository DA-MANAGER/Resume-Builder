<header id="header" class="site-header">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="{{ route('index') }}">{{ config('app.name', 'Resume Builder') }}</a>

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExample07" aria-controls="navbarsExample07" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarsExample07">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="{{ route('index') }}">Home <span class="sr-only">(current)</span></a>
                    </li>

                    @auth
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="dropdown-profile" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{ Auth::user()->name }}</a>

                        <div class="dropdown-menu" aria-labelledby="dropdown-profile">
                            <a class="dropdown-item" href="{{ route('resumes.create') }}">New Resume</a>
                            <a class="dropdown-item" href="{{ route('dashboard.statistics', ['username' => Auth::user()->username]) }}">Dashboard</a>

                            <form class="dropdown-item px-0 py-0" action="{{ route('logout') }}" method="POST">
                                {{ csrf_field() }}

                                <button type="submit" class="btn btn-link btn-block px-4 text-left">Log out</button>
                            </form>
                        </div>
                    </li>
                    @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Sign in</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">Sign up</a>
                    </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>
</header>