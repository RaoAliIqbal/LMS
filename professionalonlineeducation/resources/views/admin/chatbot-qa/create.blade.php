@extends('../admin/layouts.app')

@section('template_title')
    Add Chatbot Q&amp;A tip
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                @includeif('partials.errors')
                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">Add support chatbot tip</span>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.chatbot-qas.store') }}" role="form">
                            @csrf
                            @include('admin.chatbot-qa._form', ['item' => $item])
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
