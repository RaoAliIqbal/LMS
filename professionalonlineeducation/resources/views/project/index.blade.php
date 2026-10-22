@extends('../admin/layouts.app')

@section('template_title')
    Project
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Project') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('projects.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
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
                                        
										<th>User Id</th>
										<th>Project Name</th>
										<th>Project Description</th>
										<th>Start Date</th>
										<th>End Date</th>
										<th>Project File</th>
										<th>Status</th>
										<th>Added By</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($projects as $project)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
											<td>{{ $project->user_id }}</td>
											<td>{{ $project->project_name }}</td>
											<td>{{ $project->project_description }}</td>
											<td>{{ $project->start_date }}</td>
											<td>{{ $project->end_date }}</td>
											<td>{{ $project->project_file }}</td>
											<td>{{ $project->status }}</td>
											<td>{{ $project->added_by }}</td>

                                            <td>
                                                <form action="{{ route('projects.destroy',$project->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('projects.show',$project->id) }}"><i class="fa fa-fw fa-eye"></i> Show</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('projects.edit',$project->id) }}"><i class="fa fa-fw fa-edit"></i> Edit</a>
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
                {!! $projects->links() !!}
            </div>
        </div>
    </div>
@endsection
