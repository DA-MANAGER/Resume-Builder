<div class="work-experience">
    <div class="row">
        <div class="col-sm-12">
            <strong>
                @if (! empty($data[0]->employerName)) {{ $data[0]->employerName }} @endif
                @if (! empty($data[0]->city)) {{ ', ' . $data[0]->city }} @endif
                @if (! empty($data[0]->country)) {{ ', ' . $data[0]->country }} @endif
            </strong><br>

            <span class="has-color">
                @if (! empty($data[1]->positionTitle)) {{ $data[1]->positionTitle }} @endif
                @if (! empty($data[1]->startMonth)) {{ ', ' . $data[1]->startMonth }} @endif
                @if (! empty($data[1]->startYear)) {{ ' ' . $data[1]->startYear }} @endif

                @if ((bool) $data[1]->stillWork)
                    - Present
                @else
                    @if (! empty($data[1]->endMonth)) {{ ' - ' . $data[1]->endMonth }} @endif
                    @if (! empty($data[1]->endYear)) {{ ' ' . $data[1]->endYear }} @endif
                @endif
            </span>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <ul>
                @foreach($data[2]->items as $item)
                    <li>{{ $item }}</li>
                @endforeach
            </ul>
        </div>
    </div>
</div>