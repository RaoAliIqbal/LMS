<div class="box box-info padding-1">
    <div class="box-body">
       <div class="row" >
           <div class="col-md-12 form-group">
            {{ Form::label('seminar_id') }}
            {{ Form::text('seminar_id', $seminar->seminar_id, ['class' => 'form-control' . ($errors->has('seminar_id') ? ' is-invalid' : ''), 'placeholder' => 'Seminar Id']) }}
            {!! $errors->first('seminar_id', '<div class="invalid-feedback">:message</p>') !!}
        </div>
       </div>
       <div class="row" >
           <div class="col-md-12 form-group">
            {{ Form::label('max_students') }}
            {{ Form::number('max_students', $seminar->max_students, ['class' => 'form-control' . ($errors->has('max_students') ? ' is-invalid' : ''), 'placeholder' => 'Max Students']) }}
            {!! $errors->first('max_students', '<div class="invalid-feedback">:message</p>') !!}
        </div>
       </div>
       <div class="row" >
           <div class="col-md-12 form-group">
            {{ Form::label('course_title') }}
            {{ Form::text('course_title', $seminar->course_title, ['class' => 'form-control' . ($errors->has('course_title') ? ' is-invalid' : ''), 'placeholder' => 'Course Title']) }}
            {!! $errors->first('course_title', '<div class="invalid-feedback">:message</p>') !!}
        </div>
       </div>
       <div class="row" >
           <div class="col-md-12 form-group">
            {{ Form::label('class_full_message') }}
            {{ Form::text('class_full_message', $seminar->class_full_message, ['class' => 'form-control' . ($errors->has('class_full_message') ? ' is-invalid' : ''), 'placeholder' => 'Class Full Message']) }}
            {!! $errors->first('class_full_message', '<div class="invalid-feedback">:message</p>') !!}
        </div>
       </div>
       <div class="row" >
           <div class="col-md-6 form-group">
            {{ Form::label('start_date') }}
            {{ Form::date('start_date', $seminar->start_date, ['class' => 'form-control' . ($errors->has('start_date') ? ' is-invalid' : ''), 'placeholder' => 'Start Date']) }}
            {!! $errors->first('start_date', '<div class="invalid-feedback">:message</p>') !!}
        </div>
           <div class="col-md-6 form-group">
               {{ Form::label('end_date') }}
               {{ Form::date('end_date', $seminar->end_date, ['class' => 'form-control' . ($errors->has('end_date') ? ' is-invalid' : ''), 'placeholder' => 'End Date']) }}
               {!! $errors->first('end_date', '<div class="invalid-feedback">:message</p>') !!}
           </div>
       </div>
       <div class="row" >
           <div class="col-md-6 form-group">
            {{ Form::label('start_time') }}
            {{ Form::time('start_time', $seminar->start_time, ['class' => 'form-control' . ($errors->has('start_time') ? ' is-invalid' : ''), 'placeholder' => 'Start Time']) }}
            {!! $errors->first('start_time', '<div class="invalid-feedback">:message</p>') !!}
        </div>
           <div class="col-md-6 form-group">
               {{ Form::label('end_time') }}
               {{ Form::time('end_time', $seminar->end_time, ['class' => 'form-control' . ($errors->has('end_time') ? ' is-invalid' : ''), 'placeholder' => 'End Time']) }}
               {!! $errors->first('end_time', '<div class="invalid-feedback">:message</p>') !!}
           </div>
       </div>
       <div class="row" >

       </div>
       <div class="row" >
           <div class="col-md-12 form-group">
            {{ Form::label('location') }}
            {{ Form::text('location', $seminar->location, ['class' => 'form-control' . ($errors->has('location') ? ' is-invalid' : ''), 'placeholder' => 'Location']) }}
            {!! $errors->first('location', '<div class="invalid-feedback">:message</p>') !!}
        </div>
       </div>
       <div class="row" >
           <div class="col-md-12 form-group">
            {{ Form::label('address') }}
            {{ Form::text('address', $seminar->address, ['class' => 'form-control' . ($errors->has('address') ? ' is-invalid' : ''), 'placeholder' => 'Address']) }}
            {!! $errors->first('address', '<div class="invalid-feedback">:message</p>') !!}
        </div>
       </div>
       <div class="row" >
           <div class="col-md-12 form-group">
            {{ Form::label('city') }}
            {{ Form::text('city', $seminar->city, ['class' => 'form-control' . ($errors->has('city') ? ' is-invalid' : ''), 'placeholder' => 'City']) }}
            {!! $errors->first('city', '<div class="invalid-feedback">:message</p>') !!}
        </div>
       </div>
       <div class="row" >
           <div class="col-md-12 form-group">
            {{ Form::label('state') }}
            {{ Form::text('state', $seminar->state, ['class' => 'form-control' . ($errors->has('state') ? ' is-invalid' : ''), 'placeholder' => 'State']) }}
            {!! $errors->first('state', '<div class="invalid-feedback">:message</p>') !!}
        </div>
       </div>
       <div class="row" >
           <div class="col-md-12 form-group">
            {{ Form::label('zip_code') }}
            {{ Form::text('zip_code', $seminar->zip_code, ['class' => 'form-control' . ($errors->has('zip_code') ? ' is-invalid' : ''), 'placeholder' => 'Zip Code']) }}
            {!! $errors->first('zip_code', '<div class="invalid-feedback">:message</p>') !!}
        </div>
       </div>
       <div class="row" >
           <div class="col-md-12 form-group">
            {{ Form::label('ceus') }}
            {{ Form::text('ceus', $seminar->ceus, ['class' => 'form-control' . ($errors->has('ceus') ? ' is-invalid' : ''), 'placeholder' => 'Ceus']) }}
            {!! $errors->first('ceus', '<div class="invalid-feedback">:message</p>') !!}
        </div>
       </div>
       <div class="row" >
           <div class="col-md-12 form-group">
            {{ Form::label('cost') }}
            {{ Form::text('cost', $seminar->cost, ['class' => 'form-control' . ($errors->has('cost') ? ' is-invalid' : ''), 'placeholder' => 'Cost']) }}
            {!! $errors->first('cost', '<div class="invalid-feedback">:message</p>') !!}
        </div>
       </div>
       <div class="row" >
           <div class="col-md-12 form-group">
            {{ Form::label('description') }}
            {{ Form::textarea('description', $seminar->description, ['class' => 'form-control' . ($errors->has('description') ? ' is-invalid' : ''), 'placeholder' => 'Description']) }}
            {!! $errors->first('description', '<div class="invalid-feedback">:message</p>') !!}
        </div>
       </div>

       {{-- ══════════════════════════════════════
            ZOOM / LIVE CLASS SECTION
       ══════════════════════════════════════ --}}
       <div class="row">
           <div class="col-md-12">
               <hr>
               <h5 style="color:#2c6fad;"><i class="fa fa-video-camera"></i> Zoom / Live Class Settings</h5>
           </div>
       </div>

       <div class="row">
           <div class="col-md-12 form-group">
               <label>
                   <input type="checkbox" name="is_online" value="1"
                       {{ old('is_online', $seminar->is_online ?? 0) ? 'checked' : '' }}>
                   &nbsp; This is an <strong>online/hybrid</strong> class (show Zoom details to enrolled students)
               </label>
           </div>
       </div>

       <div class="row">
           <div class="col-md-12 form-group">
               {{ Form::label('zoom_link', 'Zoom Join Link') }}
               {{ Form::text('zoom_link', $seminar->zoom_link ?? '', ['class' => 'form-control', 'placeholder' => 'https://zoom.us/j/123456789?pwd=...']) }}
               <small class="text-muted">Paste the full Zoom join URL here. Only shown to enrolled students.</small>
           </div>
       </div>

       <div class="row">
           <div class="col-md-6 form-group">
               {{ Form::label('zoom_meeting_id', 'Meeting ID') }}
               {{ Form::text('zoom_meeting_id', $seminar->zoom_meeting_id ?? '', ['class' => 'form-control', 'placeholder' => '123 456 7890']) }}
           </div>
           <div class="col-md-6 form-group">
               {{ Form::label('zoom_password', 'Meeting Password / Passcode') }}
               {{ Form::text('zoom_password', $seminar->zoom_password ?? '', ['class' => 'form-control', 'placeholder' => 'abc123']) }}
           </div>
       </div>

    </div>
    <div class="box-footer mt20">
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</div>
