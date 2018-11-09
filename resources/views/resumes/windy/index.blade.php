@push('app_header')
    <style>
        .section-heading {
            border-bottom: 1px solid #333;
            font-weight: bold;
            padding-bottom: 5px;
        }

        .section ul {
            margin-top: 15px;
        }

        .section ul li {
            margin-bottom: 3px;
            padding-left: 15px;
        }
    </style>
@endpush

@extends('resumes.base')

@section('app_content')
    <header class="text-center">
        <h2>
            <strong>{{ $contact_info->data[0]->firstName . ' ' . $contact_info->data[0]->lastName}}</strong>
        </h2>

        <p>
            {{ $contact_info->data[0]->address1 }}
            {{ $contact_info->data[0]->city }}

            @if (! empty($contact_info->data[0]->postCode)) {{ ' (' . $contact_info->data[0]->postCode . ')' }} @endif
            @if (! empty($contact_info->data[0]->country)) {{ ', ' . $contact_info->data[0]->country }} @endif

            @if (! empty($contact_info->data[0]->phone)) {{ $contact_info->data[0]->phone }} @endif
            @if (! empty($contact_info->data[0]->email)) {{ $contact_info->data[0]->email }} @endif
        </p>
    </header>

    <div>
        @foreach($data as $section)
            @if ($section->type !== 'contact-information')
                <div class="section">
                    <h3 class="section-heading">{{ $section->name }}</h3>

                    @include('resumes.' . $template . '.sections.' . $section->type, ['data' => $section->data])
                </div>
            @endif
        @endforeach
    </div>
@endsection