@extends('../admin/layouts.app')

@section('template_title')
    Edit Chatbot Q&amp;A tip
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                @includeif('partials.errors')
                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">Edit tip #{{ $item->id }}</span>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.chatbot-qas.update', $item->id) }}" role="form">
                            @csrf
                            @method('PUT')
                            @include('admin.chatbot-qa._form', ['item' => $item])
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
