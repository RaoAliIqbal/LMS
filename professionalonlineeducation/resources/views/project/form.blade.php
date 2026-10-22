<div class="box box-info padding-1">
    <div class="box-body">
        
        <div class="form-group">
            {{ Form::label('user_id') }}
            {{ Form::text('user_id', $project->user_id, ['class' => 'form-control' . ($errors->has('user_id') ? ' is-invalid' : ''), 'placeholder' => 'User Id']) }}
            {!! $errors->first('user_id', '<div class="invalid-feedback">:message</p>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('project_name') }}
            {{ Form::text('project_name', $project->project_name, ['class' => 'form-control' . ($errors->has('project_name') ? ' is-invalid' : ''), 'placeholder' => 'Project Name']) }}
            {!! $errors->first('project_name', '<div class="invalid-feedback">:message</p>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('project_description') }}
            {{ Form::text('project_description', $project->project_description, ['class' => 'form-control' . ($errors->has('project_description') ? ' is-invalid' : ''), 'placeholder' => 'Project Description']) }}
            {!! $errors->first('project_description', '<div class="invalid-feedback">:message</p>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('start_date') }}
            {{ Form::text('start_date', $project->start_date, ['class' => 'form-control' . ($errors->has('start_date') ? ' is-invalid' : ''), 'placeholder' => 'Start Date']) }}
            {!! $errors->first('start_date', '<div class="invalid-feedback">:message</p>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('end_date') }}
            {{ Form::text('end_date', $project->end_date, ['class' => 'form-control' . ($errors->has('end_date') ? ' is-invalid' : ''), 'placeholder' => 'End Date']) }}
            {!! $errors->first('end_date', '<div class="invalid-feedback">:message</p>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('project_file') }}
            {{ Form::text('project_file', $project->project_file, ['class' => 'form-control' . ($errors->has('project_file') ? ' is-invalid' : ''), 'placeholder' => 'Project File']) }}
            {!! $errors->first('project_file', '<div class="invalid-feedback">:message</p>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('status') }}
            {{ Form::text('status', $project->status, ['class' => 'form-control' . ($errors->has('status') ? ' is-invalid' : ''), 'placeholder' => 'Status']) }}
            {!! $errors->first('status', '<div class="invalid-feedback">:message</p>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('added_by') }}
            {{ Form::text('added_by', $project->added_by, ['class' => 'form-control' . ($errors->has('added_by') ? ' is-invalid' : ''), 'placeholder' => 'Added By']) }}
            {!! $errors->first('added_by', '<div class="invalid-feedback">:message</p>') !!}
        </div>

    </div>
    <div class="box-footer mt20">
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</div>