@if(Session::has('error'))
    <div class="alert alert-danger">
        <ul>
            <li>{!! Session::get('error') !!}</li>
        </ul>
    </div>
@endif
@if(Session::has('info'))
    <p class="alert alert-info">{!! Session::get('info') !!}</p>
@endif
@if(Session::has('success'))
    <p class="alert alert-info">{!! Session::get('success') !!}</p>
@endif

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{!! $error !!}</li>
            @endforeach
        </ul>
    </div>
@endif

