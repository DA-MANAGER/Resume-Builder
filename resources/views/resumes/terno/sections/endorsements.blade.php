<div class="endorsements">
    <div class="row">
        <div class="col-sm-12">
            <ul>
                @foreach($data[0]->items as $item)
                    <li>{{ $item }}</li>
                @endforeach
            </ul>
        </div>
    </div>
</div>