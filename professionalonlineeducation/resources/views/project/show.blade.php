@extends('../admin/layouts.app')

@section('template_title')
    {{ $project->name ?? 'Show Project' }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="float-left">
                            <span class="card-title">Show Project</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('projects.index') }}"> Back</a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>User Id:</strong>
                            {{ $project->user_id }}
                        </div>
                        <div class="form-group">
                            <strong>Project Name:</strong>
                            {{ $project->project_name }}
                        </div>
                        <div class="form-group">
                            <strong>Project Description:</strong>
                            {{ $project->project_description }}
                        </div>
                        <div class="form-group">
                            <strong>Start Date:</strong>
                            {{ $project->start_date }}
                        </div>
                        <div class="form-group">
                            <strong>End Date:</strong>
                            {{ $project->end_date }}
                        </div>
                        <div class="form-group">
                            <strong>Project File:</strong>
                            {{ $project->project_file }}
                        </div>
                        <div class="form-group">
                            <strong>Status:</strong>
                            {{ $project->status }}
                        </div>
                        <div class="form-group">
                            <strong>Added By:</strong>
                            {{ $project->added_by }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
