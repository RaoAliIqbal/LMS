<div class="box box-info padding-1">
    <div class="box-body">

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('state_id') }}
                    <select name="state_id" class="form-control" id="">
                        @foreach($states as $state)
                            <option {{ ($regulation->state_id == $state->id) ? 'selected':'' }} value="{{ $state->id }}">{{ $state->name }}</option>
                        @endforeach
                    </select>
                    {!! $errors->first('state_id', '<div class="invalid-feedback">:message</p>') !!}
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('category_id') }}
                    <select name="category_id" class="form-control" id="">
                        @foreach($categories as $category)
                            <option {{ ($regulation->category_id == $category->id) ? 'selected':'' }} value="{{ $category->id }}">{{ $category->title }}</option>
                        @endforeach
                    </select>
                    {!! $errors->first('category_id', '<div class="invalid-feedback">:message</p>') !!}
                </div>
            </div>
        </div>

        <div class="form-group">
            {{ Form::label('title') }}
            {{ Form::text('title', $regulation->title, ['class' => 'form-control' . ($errors->has('title') ? ' is-invalid' : ''), 'placeholder' => 'Title']) }}
            {!! $errors->first('title', '<div class="invalid-feedback">:message</p>') !!}
        </div>

        <div class="form-group">
            {{ Form::label('description') }}
            <textarea name="description" class="html form-control">{{ $regulation->description }}</textarea>
            {!! $errors->first('description', '<div class="invalid-feedback">:message</p>') !!}
        </div>

        <div class="form-group">
            {{ Form::label('link') }}
            {{ Form::text('link', $regulation->link, ['class' => 'form-control' . ($errors->has('link') ? ' is-invalid' : ''), 'placeholder' => 'Link']) }}
            {!! $errors->first('link', '<div class="invalid-feedback">:message</p>') !!}
        </div>

    </div>
    <div class="box-footer mt20">
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</div>
