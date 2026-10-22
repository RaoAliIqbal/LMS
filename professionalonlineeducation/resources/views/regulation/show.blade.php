@extends('../admin/layouts.app')

@section('template_title')
    {{ $regulation->name ?? 'Show Regulation' }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="float-left">
                            <span class="card-title">Show Regulation</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('regulations.index') }}"> Back</a>
                        </div>
                    </div>

                    <div class="card-body">

                        <div class="form-group">
                            <strong>State:</strong>
                            {{ $regulation->state->name }}
                        </div>
                        <div class="form-group">
                            <strong>Category:</strong>
                            {{ $regulation->category->title }}
                        </div>
                        <div class="form-group">
                            <strong>Title:</strong>
                            {{ $regulation->title }}
                        </div>
                        <div class="form-group">
                            <strong>Description:</strong>
                            {!! $regulation->description !!}
                        </div>

                        <div class="form-group">
                            <strong>Link:</strong>
                            {{ $regulation->link }}
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
