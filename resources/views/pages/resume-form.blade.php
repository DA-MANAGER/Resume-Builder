@extends('app')

@section('app_content')
    <section class="resume-new">
        <div class="container">
            <div class="row my-4">
                <div class="col-md-9">
                    @if (isset($data))
                        <resume-form-component
                                author="{{ $author }}"
                                data="{{ $data }}"
                                form_action_url="{{ $form_action_url }}"
                                form_method="{{ $form_method }}"
                                name="{{ $title }}"
                                template="{{ $template }}"
                                user="{{ $user }}"></resume-form-component>
                    @else
                        <resume-form-component
                                author="{{ $author }}"
                                form_action_url="{{ $form_action_url }}"
                                form_method="{{ $form_method }}"
                                name="{{ $title }}"
                                template="{{ $template }}"
                                user="{{ $user }}"></resume-form-component>
                    @endif
                </div>

                <div class="col-md">
                    @include('partials._resume-action-card')
                </div>
            </div>
        </div>

        <resume-add-section-modal-component></resume-add-section-modal-component>

        @if (isset($resume_id))
            <resume-preview-modal-component
                form_action_url="{{ route('resumes.download', ['resume_id' => $resume_id]) }}"
                form_method="POST"></resume-preview-modal-component>
        @else
        <resume-preview-modal-component></resume-preview-modal-component>
        @endif

        <resume-select-template-modal-component></resume-select-template-modal-component>
    </section>
@endsection
