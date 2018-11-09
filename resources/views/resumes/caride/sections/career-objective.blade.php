<div class="career-objective">
    <div class="row">
        <div class="col-sm-12">
            @foreach($data[0]->items as $item)
                <p>{{ $item }}</p>
            @endforeach
        </div>
    </div>
</div>