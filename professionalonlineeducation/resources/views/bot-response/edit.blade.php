@extends('../admin/layouts.app')

@section('template_title')
    Update Bot Response
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="">
            <div class="col-md-12">

                @includeif('partials.errors')

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">Update Bot Response</span>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('bot-responses.update', $botResponse->id) }}"  role="form" enctype="multipart/form-data">
                            {{ method_field('PATCH') }}
                            @csrf

                            @include('bot-response.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
