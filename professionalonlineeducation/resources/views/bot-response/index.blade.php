@extends('../admin/layouts.app')

@section('template_title')
    Bot Response
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Bot Response') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('bot-responses.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
                                  {{ __('Create New') }}
                                </a>
                              </div>
                        </div>
                    </div>
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success">
                            <p>{{ $message }}</p>
                        </div>
                    @endif

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="thead">
                                    <tr>
                                        <th>No</th>

										<th>Message</th>
										<th>Keywords</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($botResponses as $botResponse)

                                        <tr>
                                            <td>{{ ++$i }}</td>

											<td>{{ $botResponse->message }}</td>
											<td>
                                                @foreach($botResponse->keywordsArray as $keyword)
                                                    <span class="label label-info">{{ $keyword }}</span>
                                                @endforeach
                                            </td>



                                            <td>
                                                <form action="{{ route('bot-responses.destroy',$botResponse->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('bot-responses.show',$botResponse->id) }}"><i class="fa fa-fw fa-eye"></i> Show</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('bot-responses.edit',$botResponse->id) }}"><i class="fa fa-fw fa-edit"></i> Edit</a>
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-fw fa-trash"></i> Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {!! $botResponses->links() !!}
            </div>
        </div>
    </div>
@endsection
