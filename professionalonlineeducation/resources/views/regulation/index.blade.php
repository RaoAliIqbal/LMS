@extends('../admin/layouts.app')

@section('template_title')
    Regulation
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Regulation') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('regulations.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
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

										<th>State</th>
										<th>Category</th>
                                        <th>Title</th>
										<th>Link</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($regulations as $regulation)
                                        <tr>
                                            <td>{{ ++$i }}</td>

											<td>{{ @$regulation->state->name }}</td>
											<td>{{ @$regulation->category->title }}</td>
											<td>{{ $regulation->title }}</td>
											<td>
                                                @if($regulation->link != null)
                                                    <a href="{{ $regulation->link }}" target="_blank">
                                                    <i class="fa fa-share-square-o"></i>
                                                    </a>
                                                @else
                                                    None
                                                @endif
                                            <td>
                                                <form action="{{ route('regulations.destroy',$regulation->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('regulations.show',$regulation->id) }}"><i class="fa fa-fw fa-eye"></i> Show</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('regulations.edit',$regulation->id) }}"><i class="fa fa-fw fa-edit"></i> Edit</a>
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
                {!! $regulations->links() !!}
            </div>
        </div>
    </div>
@endsection
