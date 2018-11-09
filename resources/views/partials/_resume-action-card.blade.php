<div class="card shadow-sm">
    <div class="card-body">
        @auth
            <div>
                <div class="row">
                    <div class="col-sm">
                        <resume-display-template-name-component></resume-display-template-name-component>
                    </div>

                    <div class="col-sm">
                        <div class="mt-1 text-right">
                            @if (isset($resume_id))
                                <resume-download-form-component
                                    form_action_url="{{ route('resumes.download', ['resume_id' => $resume_id]) }}"></resume-download-form-component>

                                <form class="d-inline" name="delete-resume" action="{{ route('resumes.destroy', ['resume_id' => $resume_id]) }}" method="post">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="btn btn-danger">
                                        <i class="fa-trash"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>

                <hr>
            </div>

            <div>
                @if (! empty($user) && $user->hasAnyRole(['administrator', 'moderator']))
                    <resume-assign-author-component
                        avatar="{{ asset( 'images/avatar.png' ) }}"></resume-assign-author-component>
                @else
                    <div class="py-2">
                        <div>
                            <div class="d-inline mr-2">
                                @if (! empty($author->avatar))
                                    <img class="rounded-circle" style="width: 45px; height: 45px;" src="{{ $author->avatar }}">
                                @else
                                    <img class="rounded-circle" style="width: 45px; height: 45px;" src="{{ asset('images/avatar.png' ) }}">
                                @endif
                            </div>

                            <div class="d-inline">
                                <span>{{ $author->name }}</span>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @else
            <div>
                <div class="row">
                    <div class="col-sm">
                        <resume-display-template-name-component></resume-display-template-name-component>
                    </div>
                </div>

                <hr>
            </div>

            <div>
                <p class="lead">We can save resume to your cloud automatically.</p>

                <a class="btn btn-outline-primary" href="{{ route('register') }}">Get started</a>
            </div>
        @endauth
    </div>
</div>