@extends('../admin/layouts.app')

@section('template_title')
    {{ $botResponse->name ?? 'Show Bot Response' }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="float-left">
                            <span class="card-title">Show Bot Response</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('bot-responses.index') }}"> Back</a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>Message:</strong>
                            {{ $botResponse->message }}
                        </div>
                        <div class="form-group">
                            <strong>Keywords:</strong>
                            {{ $botResponse->keywords }}
                        </div>
                        <div class="form-group">
                            <strong>Status:</strong>
                            {{ $botResponse->status }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
