@extends('admin.layouts.app')

@section('title')
{{ $pageData['name'] }}
@endsection
@section('content')
    <div class="content-main " id="content-main">
        <!-- ############ Main START-->
        <div class="padding">
            <div class="row">
                <div class="col-md-12">
                    @include('layouts.partials.alertBox')
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                            <div class="box-header">
                                <h2>{{ $pageData['name']  }}</h2>
                            </div>
                            {!! Form::open(['method'=>'POST','class'=>'validate','files'=>true,'action'=>['EducationController@lessonsSave']]) !!}
                           @if($pageData['type'] == 'edit')
                                <input type="hidden" value="{{ $lesson->id }}" name="id">
                            @endif
                            <div class="box-body profileView">
                                <div class="form-row">
                                    <div class="form-group col-sm-12">
                                        <label>Official Name*</label>
                                        {{ Helper::input('title','Title',isset($lesson->title)?$lesson->title:'',true,['id'=>'title'],null) }}

                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-sm-12">
                                        <label>Catalog Name*</label>
                                        {{ Helper::input('title_two','Title',isset($lesson->title_two)?$lesson->title_two:'',true,['id'=>'title'],null) }}

                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-sm-12">
                                        <label>Catalog Number*</label>
                                        {{ Helper::input('title_dev','Title',isset($lesson->title_dev)?$lesson->title_dev:'',true,['id'=>'title'],null) }}

                                    </div>
                                </div>
                                <div class="form-row both">
                                    <div class="form-group col-sm-8">
                                        <label>Description*</label>
                                        <textarea name="description" class='html'>{{ $lesson->description }}</textarea>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <div class="form-group">
                                            <label>Total Hours*</label>
                                            <input type="number"  name="hours" value="{{ $lesson->hours }}" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label>Price*</label>
                                            <input type="number"  name="price" value="{{ $lesson->price }}" class="form-control">
                                        </div>

                                        @if(count($categories))
                                        <label>Select Categories* </label>
                                            <label class="pull-right">
                                                Select All
                                                <input data-listId="category" class="selectAll" type="checkbox" >
                                            </label>
                                        <select multiple name="category_ids[]" id="category" class="form-control">
                                            @foreach($categories as $category)
                                                <option {{ (in_array($category->id,$courseCategory))?'selected':'' }} value="{{ $category->id }}">{{ $category->title }}</option>
                                            @endforeach
                                        </select>
                                        @endif
                                            <br>
                                        @if(count($states))
                                        <label>Select States*</label>
                                        <label class="pull-right">
                                            Select All
                                            <input data-listId="state" class="selectAll" type="checkbox" >
                                        </label>
                                        <select multiple name="state_ids[]" id="state" class="form-control">
                                            @foreach($states as $state)
                                                <option {{ (in_array($state->id,$courseStates))?'selected':'' }} value="{{ $state->id }}">{{ $state->name }}</option>
                                            @endforeach
                                        </select>
                                        @endif
                                        <br>
                                        <hr>
                                        <label for="">Select Tag</label>
                                        <select name="tag" class="form-control">
                                            <option {{ ($lesson->tag == 0)?'selected':'' }} value="0">No Tag</option>
                                            <option {{ ($lesson->tag == 1)?'selected':'' }} value="1">Most Popular</option>
                                            <option {{ ($lesson->tag == 2)?'selected':'' }} value="2">New Course</option>
                                            <option {{ ($lesson->tag == 3)?'selected':'' }} value="3">FREE</option>
                                            <option {{ ($lesson->tag == 4)?'selected':'' }} value="4">Partial Course</option>
                                            <option {{ ($lesson->tag == 5)?'selected':'' }} value="5">NATIONAL</option>
                                            <option {{ ($lesson->tag == 6)?'selected':'' }} value="6">AZBCE 18-CAN-001</option>
                                            <option {{ ($lesson->tag == 7)?'selected':'' }} value="7">AZBCE 18-CAN-002</option>
                                            <option {{ ($lesson->tag == 8)?'selected':'' }} value="8">AZBCE 18-CAN-003</option>
                                            <option {{ ($lesson->tag == 9)?'selected':'' }} value="9">AZBCE 18-CAN-004</option>
                                            <option {{ ($lesson->tag == 10)?'selected':'' }} value="10">AZBCE 18-CAN-005</option>
                                            <option {{ ($lesson->tag == 11)?'selected':'' }} value="11">AZBCE 18-CAN-006</option>
                                            <option {{ ($lesson->tag == 12)?'selected':'' }} value="12">AZBCE 18-CAN-007</option>
                                            <option {{ ($lesson->tag == 13)?'selected':'' }} value="13">AZBCE 18-CAN-008</option>
                                            <option {{ ($lesson->tag == 14)?'selected':'' }} value="14">AZBCE 18-CAN-009</option>
                                            <option {{ ($lesson->tag == 15)?'selected':'' }} value="15">AZBCE 18-CAN-010</option>
                                            <option {{ ($lesson->tag == 16)?'selected':'' }} value="16">PACE Approved</option>
                                        </select>
                                            <br>
{{--                                        @if(count($approvals))--}}
{{--                                        <label>Select Approvals*</label>--}}
{{--                                        <label class="pull-right">--}}
{{--                                            Select All--}}
{{--                                            <input data-listId="approval" class="selectAll" type="checkbox" >--}}
{{--                                        </label>--}}
{{--                                        <select multiple name="approval_ids[]" id="approval" class="form-control">--}}
{{--                                            @foreach($approvals as $approval)--}}
{{--                                                <option {{ (in_array($approval->id,$courseApprovals))?'selected':'' }} value="{{ $approval->id }}">{{ $approval->approval }}</option>--}}
{{--                                            @endforeach--}}
{{--                                        </select>--}}
{{--                                        @endif--}}

                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-sm-8">
                                        <br>
                                        <br>
                                        <label>Thumbnail*</label>
                                        <input type="file"  name="thumbnail_image" accept="image/*" class="form-control">
                                    </div>
                                    @if(isset($lesson->image))
                                        <div class="form-group col-sm-4">
                                            <img src="{{ url($lesson->image) }}" width="150" alt="">
                                        </div>
                                    @endif
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-sm-8">
                                        <br>
                                        <br>
                                        <label>Video Intro*</label>
                                        <input type="file" accept="video/mp4,video/x-m4v,video/*"  name="video_intro" class="form-control">
                                    </div>
                                    @if(isset($lesson->video))
                                        <div class="form-group col-sm-4">
                                            <video width="320" height="240" controls>
                                              <source src="{{ url($lesson->video) }}" type="video/mp4">
                                              Your browser does not support the video tag.
                                            </video>

                                        </div>
                                    @endif
                                </div>
                                <div class="form-row" style="align-items:center; align-content: center">
                                    <div class="form-group col-sm-8">
                                        <br>
                                        <br>
                                        <label>PDF File*</label>
                                        <input type="file" accept="pdf"  name="pdf" class="form-control">
                                    </div>
                                    @if(isset($lesson->pdf_file))
                                        <div class="form-group col-sm-4" style="padding-top: 65px">

                                            <a href="{{ route('downloadPdf',['f'=>$lesson->pdf_file]) }}" class="btn btn-primary btn-sm">Download PDF</a>
                                        </div>
                                    @endif
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-sm-8">
                                        <label>Select Evaluation *</label>
                                        <select name="evaluation_id" class="form-control">
                                            @if(count($evaluations))
                                                <option disabled selected>Select Evaluation</option>
                                                @foreach($evaluations as $evaluation)
                                                    <option {{ ($lesson->evaluation_id == $evaluation->id)?'selected':'' }}  value="{{ $evaluation->id }}">{{ $evaluation->title }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>

                                {{-- ===== ZOOM LIVE SESSION SECTION ===== --}}
                                <hr>
                                <div class="form-row" style="margin-bottom:5px;">
                                    <div class="col-sm-12">
                                        <h4 style="color:#2d8cff; margin-bottom:4px;">
                                            <i class="fa fa-video-camera"></i> Zoom Live Session
                                        </h4>
                                        <p class="text-muted" style="margin-bottom:10px; font-size:13px;">
                                            Enable to give enrolled students access to a live Zoom class for this course.
                                        </p>
                                    </div>
                                </div>

                                <div class="form-row" style="align-items:center; margin-bottom:12px;">
                                    <div class="col-sm-12">
                                        <label style="font-weight:600; font-size:14px; cursor:pointer;">
                                            <input type="hidden" name="has_zoom" value="0">
                                            <input type="checkbox" id="has_zoom" name="has_zoom" value="1"
                                                {{ (isset($lesson->has_zoom) && $lesson->has_zoom) ? 'checked' : '' }}
                                                onchange="toggleZoomFields(this)">
                                            &nbsp; Enable Zoom Live Session for this course
                                        </label>
                                    </div>
                                </div>

                                <div id="zoomFields" style="display:{{ (isset($lesson->has_zoom) && $lesson->has_zoom) ? 'block' : 'none' }}; background:#f0f7ff; border:1px solid #b3d4ff; border-radius:8px; padding:20px; margin-bottom:16px;">

                                    {{-- ── Existing meeting badge ── --}}
                                    @if(isset($lesson->zoom_meeting_id) && $lesson->zoom_meeting_id)
                                    <div id="zoomActiveBadge" style="background:#1a73e8; color:#fff; border-radius:6px; padding:10px 14px; margin-bottom:14px; display:flex; align-items:center; justify-content:space-between;">
                                        <span><i class="fa fa-check-circle"></i> &nbsp;<strong>Meeting Active:</strong> ID {{ $lesson->zoom_meeting_id }}</span>
                                        <button type="button" class="btn btn-danger btn-xs" onclick="deleteZoomMeeting({{ $lesson->id ?? 0 }})">
                                            <i class="fa fa-trash"></i> Delete from Zoom
                                        </button>
                                    </div>
                                    @endif

                                    {{-- ── Create via API button ── --}}
                                    @if(!isset($lesson->zoom_meeting_id) || !$lesson->zoom_meeting_id)
                                    <div style="background:#fff; border:1px dashed #1a73e8; border-radius:6px; padding:14px; margin-bottom:16px; text-align:center;">
                                        <p style="margin:0 0 10px; color:#555; font-size:13px;">
                                            <i class="fa fa-magic" style="color:#1a73e8;"></i>
                                            <strong>Create a real Zoom meeting instantly via API</strong>
                                        </p>
                                        <button type="button" class="btn btn-primary" onclick="showZoomApiModal()" id="createZoomBtn">
                                            <i class="fa fa-video-camera"></i> &nbsp;Create Zoom Meeting via API
                                        </button>
                                        <p style="margin:8px 0 0; font-size:11px; color:#888;">— or fill in the details manually below —</p>
                                    </div>
                                    @endif

                                    {{-- ── API result notification ── --}}
                                    <div id="zoomApiResult" style="display:none; margin-bottom:12px;"></div>

                                    {{-- ── Manual / auto-filled fields ── --}}
                                    <div class="form-row">
                                        <div class="form-group col-sm-12">
                                            <label><i class="fa fa-link"></i> Zoom Join Link</label>
                                            <input type="url" name="zoom_link" id="f_zoom_link" class="form-control"
                                                placeholder="https://zoom.us/j/123456789"
                                                value="{{ isset($lesson->zoom_link) ? $lesson->zoom_link : '' }}">
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-sm-6">
                                            <label><i class="fa fa-hashtag"></i> Meeting ID</label>
                                            <input type="text" name="zoom_meeting_id" id="f_zoom_meeting_id" class="form-control"
                                                placeholder="e.g. 123 456 7890"
                                                value="{{ isset($lesson->zoom_meeting_id) ? $lesson->zoom_meeting_id : '' }}">
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label><i class="fa fa-lock"></i> Passcode</label>
                                            <input type="text" name="zoom_password" id="f_zoom_password" class="form-control"
                                                placeholder="e.g. abc123"
                                                value="{{ isset($lesson->zoom_password) ? $lesson->zoom_password : '' }}">
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-sm-6">
                                            <label><i class="fa fa-calendar"></i> Session Date</label>
                                            <input type="date" name="zoom_date" id="f_zoom_date" class="form-control"
                                                value="{{ isset($lesson->zoom_date) ? $lesson->zoom_date : '' }}">
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label><i class="fa fa-clock-o"></i> Session Time</label>
                                            <input type="time" name="zoom_time" id="f_zoom_time" class="form-control"
                                                value="{{ isset($lesson->zoom_time) ? $lesson->zoom_time : '' }}">
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-sm-12">
                                            <label><i class="fa fa-info-circle"></i> Session Notes (optional)</label>
                                            <textarea name="zoom_description" id="f_zoom_description" class="form-control" rows="2"
                                                placeholder="e.g. Please log in 5 minutes early. Recording will be available after.">{{ isset($lesson->zoom_description) ? $lesson->zoom_description : '' }}</textarea>
                                        </div>
                                    </div>

                                    {{-- Host start URL (hidden, shown after creation) --}}
                                    @if(isset($lesson->zoom_meeting_id) && $lesson->zoom_meeting_id)
                                    <div style="background:#fff3cd; border-radius:5px; padding:8px 12px; font-size:12px; margin-top:4px;">
                                        <i class="fa fa-info-circle" style="color:#856404;"></i>
                                        <strong>Note:</strong> Save the form after editing any Zoom field to keep them in sync.
                                    </div>
                                    @endif

                                </div>
                                {{-- ===== END ZOOM SECTION ===== --}}

                                {{-- ══ ZOOM API MODAL ══ --}}
                                <div id="zoomApiModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.55); z-index:99999; align-items:center; justify-content:center;">
                                    <div style="background:#fff; border-radius:12px; padding:28px; max-width:480px; width:90%; box-shadow:0 20px 60px rgba(0,0,0,0.25);">
                                        <h4 style="margin:0 0 18px; color:#1a73e8; font-weight:700;">
                                            <i class="fa fa-video-camera"></i> Create Zoom Meeting
                                        </h4>
                                        <div id="zoomModalError" style="display:none;" class="alert alert-danger"></div>

                                        <div class="form-group">
                                            <label><strong>Meeting Topic *</strong></label>
                                            <input type="text" id="zm_topic" class="form-control"
                                                value="{{ isset($lesson->title) ? $lesson->title : '' }}"
                                                placeholder="Course name / session topic">
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label><strong>Date *</strong></label>
                                                    <input type="date" id="zm_date" class="form-control"
                                                        value="{{ isset($lesson->zoom_date) ? $lesson->zoom_date : date('Y-m-d', strtotime('+7 days')) }}">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label><strong>Time *</strong></label>
                                                    <input type="time" id="zm_time" class="form-control"
                                                        value="{{ isset($lesson->zoom_time) ? $lesson->zoom_time : '09:00' }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label><strong>Duration (minutes) *</strong></label>
                                                    <select id="zm_duration" class="form-control">
                                                        <option value="30">30 min</option>
                                                        <option value="60" selected>1 hour</option>
                                                        <option value="90">1.5 hours</option>
                                                        <option value="120">2 hours</option>
                                                        <option value="180">3 hours</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label><strong>Timezone</strong></label>
                                                    <select id="zm_timezone" class="form-control">
                                                        <option value="America/Los_Angeles">Pacific (PT)</option>
                                                        <option value="America/Denver">Mountain (MT)</option>
                                                        <option value="America/Chicago">Central (CT)</option>
                                                        <option value="America/New_York">Eastern (ET)</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label><strong>Agenda / Notes</strong></label>
                                            <textarea id="zm_agenda" class="form-control" rows="2"
                                                placeholder="Session notes for students...">{{ isset($lesson->zoom_description) ? $lesson->zoom_description : '' }}</textarea>
                                        </div>

                                        <div style="display:flex; gap:10px; margin-top:6px;">
                                            <button type="button" class="btn btn-primary flex-fill" onclick="createZoomMeeting()" id="zm_submit">
                                                <i class="fa fa-video-camera"></i> Create Meeting
                                            </button>
                                            <button type="button" class="btn btn-default" onclick="closeZoomModal()" style="width:100px;">
                                                Cancel
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                {{-- ══ END MODAL ══ --}}

                            <div class="row">
                                <div class="col-md-12">
                                    <hr>
                                    <div class="text-right">
                                        {{ Form::submit('Save',['class'=>'btn btn-primary cstm_btn']) }}
                                    </div>
                                </div>
                            </div>
                            </div>
                            {!! Form::close() !!}
                        </div>

                </div>

            </div>

            <br>


        </div>
        <!-- ############ Main END-->
    </div>


@endsection
@push('scripts')
    <script>
        $(".selectAll").change(function (){
            let ele = $(this);
            let listId = ele.attr('data-listId');
            let isChecked = $(this).is(':checked');
            $('#'+listId+' option').prop('selected', isChecked)
        });
        $('select').change(function (){
            $(this).prev().find('.selectAll').prop('checked',false);
        });

        // Zoom section toggle
        function toggleZoomFields(checkbox) {
            if (checkbox.checked) {
                document.getElementById('zoomFields').style.display = 'block';
            } else {
                document.getElementById('zoomFields').style.display = 'none';
            }
        }

        // ── Zoom API Modal ──────────────────────────────────────────────────
        function showZoomApiModal() {
            document.getElementById('zoomApiModal').style.display = 'flex';
            document.getElementById('zoomModalError').style.display = 'none';
            document.getElementById('zoomModalError').innerHTML = '';
        }

        function closeZoomModal() {
            document.getElementById('zoomApiModal').style.display = 'none';
        }

        // Close modal when clicking outside the box
        document.getElementById('zoomApiModal').addEventListener('click', function(e) {
            if (e.target === this) closeZoomModal();
        });

        // ── Create Zoom Meeting via API ─────────────────────────────────────
        function createZoomMeeting() {
            var topic    = document.getElementById('zm_topic').value.trim();
            var date     = document.getElementById('zm_date').value;
            var time     = document.getElementById('zm_time').value;
            var duration = document.getElementById('zm_duration').value;
            var timezone = document.getElementById('zm_timezone').value;
            var agenda   = document.getElementById('zm_agenda').value.trim();

            // Basic validation
            if (!topic || !date || !time) {
                var errDiv = document.getElementById('zoomModalError');
                errDiv.style.display = 'block';
                errDiv.innerHTML = '<i class="fa fa-exclamation-circle"></i> Please fill in Topic, Date, and Time.';
                return;
            }

            var btn = document.getElementById('zm_submit');
            btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Creating...';
            btn.disabled = true;
            document.getElementById('zoomModalError').style.display = 'none';

            // Get lesson ID from hidden input (only present on edit pages)
            var lessonInput = document.querySelector('input[name="id"]');
            var lessonId = lessonInput ? lessonInput.value : '';

            fetch('{{ route("admin_zoom_create") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify({
                    lesson_id : lessonId,
                    topic     : topic,
                    zoom_date : date,
                    zoom_time : time,
                    duration  : parseInt(duration),
                    timezone  : timezone,
                    agenda    : agenda,
                })
            })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                btn.innerHTML = '<i class="fa fa-video-camera"></i> Create Meeting';
                btn.disabled = false;

                if (data.success) {
                    // ── Auto-fill the form fields ──
                    document.getElementById('f_zoom_link').value       = data.zoom_link      || '';
                    document.getElementById('f_zoom_meeting_id').value = data.meeting_id     || '';
                    document.getElementById('f_zoom_password').value   = data.zoom_password  || '';
                    document.getElementById('f_zoom_date').value       = date;
                    document.getElementById('f_zoom_time').value       = time;
                    if (agenda) document.getElementById('f_zoom_description').value = agenda;

                    // ── Close modal ──
                    closeZoomModal();

                    // ── Show success notification ──
                    var result = document.getElementById('zoomApiResult');
                    result.style.display = 'block';
                    result.innerHTML =
                        '<div style="background:#d4edda; border:1px solid #c3e6cb; border-radius:6px; padding:12px 16px; font-size:13px;">' +
                        '   <strong style="color:#155724;"><i class="fa fa-check-circle"></i> Zoom Meeting Created!</strong><br>' +
                        '   <span style="color:#155724;">Meeting ID: <code>' + data.meeting_id + '</code> &nbsp;|&nbsp; Topic: ' + data.topic + '</span><br>' +
                        '   <span style="color:#155724;">Password: <code>' + data.zoom_password + '</code></span><br><br>' +
                        '   <a href="' + data.start_url + '" target="_blank" class="btn btn-success btn-xs"><i class="fa fa-play"></i> Start Meeting (Host)</a>' +
                        '   &nbsp;<a href="' + data.zoom_link + '" target="_blank" class="btn btn-primary btn-xs"><i class="fa fa-video-camera"></i> Join Link</a>' +
                        '   &nbsp;<strong style="color:#856404;"><i class="fa fa-exclamation-triangle"></i> Click Save below to save these details to the course.</strong>' +
                        '</div>';

                    // ── Show/hide the active badge vs create button ──
                    var createBox = document.querySelector('#zoomFields > div[style*="dashed"]');
                    if (createBox) createBox.style.display = 'none';

                    // Inject active badge if not present
                    if (!document.getElementById('zoomActiveBadge')) {
                        var badge = document.createElement('div');
                        badge.id = 'zoomActiveBadge';
                        badge.style.cssText = 'background:#1a73e8;color:#fff;border-radius:6px;padding:10px 14px;margin-bottom:14px;display:flex;align-items:center;justify-content:space-between;';
                        badge.innerHTML = '<span><i class="fa fa-check-circle"></i> &nbsp;<strong>Meeting Active:</strong> ID ' + data.meeting_id + '</span>' +
                            '<button type="button" class="btn btn-danger btn-xs" onclick="deleteZoomMeeting(' + lessonId + ')"><i class="fa fa-trash"></i> Delete from Zoom</button>';
                        document.getElementById('zoomFields').insertBefore(badge, document.getElementById('zoomFields').firstChild);
                    }

                } else {
                    var errDiv = document.getElementById('zoomModalError');
                    errDiv.style.display = 'block';
                    errDiv.innerHTML = '<i class="fa fa-times-circle"></i> ' + (data.message || 'Failed to create meeting.');
                }
            })
            .catch(function(err) {
                btn.innerHTML = '<i class="fa fa-video-camera"></i> Create Meeting';
                btn.disabled = false;
                var errDiv = document.getElementById('zoomModalError');
                errDiv.style.display = 'block';
                errDiv.innerHTML = '<i class="fa fa-times-circle"></i> Network error. Please try again.';
            });
        }

        // ── Delete Zoom Meeting via API ─────────────────────────────────────
        function deleteZoomMeeting(lessonId) {
            if (!lessonId) {
                alert('Please save the course first before deleting the Zoom meeting.');
                return;
            }
            if (!confirm('Delete this Zoom meeting? It will be removed from Zoom and cleared from this course.')) return;

            fetch('/admin/zoom/delete-meeting/' + lessonId, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest',
                }
            })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (data.success) {
                    // Clear all zoom fields
                    document.getElementById('f_zoom_link').value       = '';
                    document.getElementById('f_zoom_meeting_id').value = '';
                    document.getElementById('f_zoom_password').value   = '';
                    document.getElementById('f_zoom_date').value       = '';
                    document.getElementById('f_zoom_time').value       = '';
                    document.getElementById('f_zoom_description').value = '';

                    // Remove active badge
                    var badge = document.getElementById('zoomActiveBadge');
                    if (badge) badge.remove();

                    // Hide result area
                    var result = document.getElementById('zoomApiResult');
                    result.style.display = 'none';
                    result.innerHTML = '';

                    // Show "Create" button again
                    var createBox = document.querySelector('#zoomFields > div[style*="dashed"]');
                    if (createBox) createBox.style.display = 'block';

                    // Show deleted confirmation
                    var resultDiv = document.getElementById('zoomApiResult');
                    resultDiv.style.display = 'block';
                    resultDiv.innerHTML =
                        '<div class="alert alert-warning" style="font-size:13px; margin-bottom:8px;">' +
                        '<i class="fa fa-trash"></i> Zoom meeting deleted. Save the form to update the course record.' +
                        '</div>';

                } else {
                    alert('Error: ' + (data.message || 'Could not delete meeting.'));
                }
            })
            .catch(function() {
                alert('Network error. Could not delete meeting.');
            });
        }
    </script>
@endpush
