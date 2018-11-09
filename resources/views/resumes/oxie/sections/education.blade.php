<div class="education">
    <div class="row">
        <div class="col-sm-6">
            <strong>
                @if (! empty($data[0]->schoolName)) {{ $data[0]->schoolName }} @endif
            </strong>

            <br>

            @if (! empty($data[0]->programName)) {{ $data[0]->programName  }} @endif

            @if ((bool)$data[0]->isEnrolled === true)
                {{ $data[0]->expectedCompletionMonth }}
                {{ ', ' . $data[0]->expectedCompletionYear }}
            @elseif((bool)$data[0]->isEnrolled === false)
                @if ((bool)$data[0]->isCompleted === true)
                    {{ $data[0]->completionMonth }}
                    {{ ', ' . $data[0]->completionYear }}
                @else
                    {{ $data[0]->lastYearOfEnrollmentMonth }}
                    {{ ', ' . $data[0]->lastYearOfEnrollmentYear }}
                @endif
            @endif
        </div>

        <div class="col-sm-6">
            <div class="text-right">
                <strong>
                    @if (! empty($data[0]->city)) {{ ', ' . $data[0]->city }} @endif
                    @if (! empty($data[0]->country)) {{ ', ' . $data[0]->country }} @endif
                </strong>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <ul>
                @foreach($data[1]->items as $item)
                    <li>{{ $item }}</li>
                @endforeach
            </ul>
        </div>
    </div>
</div>