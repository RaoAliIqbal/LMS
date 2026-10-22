@extends('../admin/layouts.app')

@section('template_title')
    Support Chatbot Q&amp;A
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span id="card_title">Support Chatbot Q&amp;A (troubleshooting tips)</span>
                            <a href="{{ route('admin.chatbot-qas.create') }}" class="btn btn-primary btn-sm">Add tip</a>
                        </div>
                    </div>
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success m-3"><p>{{ $message }}</p></div>
                    @endif
                    <div class="card-body">
                        <p class="text-muted">Tips are merged <strong>after</strong> the built-in defaults for each topic on the public support chatbot.</p>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Topic</th>
                                    <th>Label</th>
                                    <th>Preview</th>
                                    <th>Sort</th>
                                    <th>Active</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($items as $row)
                                    <tr>
                                        <td>{{ ++$i }}</td>
                                        <td>{{ $topicLabels[$row->topic_key] ?? $row->topic_key }}</td>
                                        <td>{{ $row->label ?: '—' }}</td>
                                        <td>{{ \Illuminate\Support\Str::limit(strip_tags($row->tip_html), 80) }}</td>
                                        <td>{{ $row->sort_order }}</td>
                                        <td>{{ $row->is_active ? 'Yes' : 'No' }}</td>
                                        <td>
                                            <form action="{{ route('admin.chatbot-qas.destroy', $row->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Delete this tip?');">
                                                @csrf
                                                @method('DELETE')
                                                <a class="btn btn-sm btn-success" href="{{ route('admin.chatbot-qas.edit', $row->id) }}">Edit</a>
                                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">{!! $items->links() !!}</div>
                </div>
            </div>
        </div>
    </div>
@endsection
