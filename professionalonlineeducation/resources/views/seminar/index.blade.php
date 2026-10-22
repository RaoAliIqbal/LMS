@extends('../admin/layouts.app')

@section('template_title')
    Seminar
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Seminar') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('seminars.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
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
										<th>Seminar Id</th>
										<th>Max Students</th>
										<th>Course Title</th>
										<th>Start Date</th>
										<th>End Date</th>
										<th>Start Time</th>
										<th>End Time</th>
										<th>Location</th>
										<th>Cost</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($seminars as $seminar)
                                        <tr>
                                            <td>{{ ++$i }}</td>
											<td>{{ $seminar->seminar_id }}</td>
											<td>{{ $seminar->max_students }}</td>
											<td>{{ $seminar->course_title }}</td>
											<td>{{ $seminar->start_date }}</td>
											<td>{{ $seminar->end_date }}</td>
											<td>{{ $seminar->start_time }}</td>
											<td>{{ $seminar->end_time }}</td>
											<td>{{ $seminar->location }}</td>
											<td>{{ $seminar->cost }}</td>

                                            <td>
                                                <form action="{{ route('seminars.destroy',$seminar->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('seminars.show',$seminar->id) }}"><i class="fa fa-fw fa-eye"></i> Show</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('seminars.edit',$seminar->id) }}"><i class="fa fa-fw fa-edit"></i> Edit</a>
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
                {!! $seminars->links() !!}
            </div>
        </div>
    </div>
@endsection
