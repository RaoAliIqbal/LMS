<div class="header" style="text-align: center">
    <h4 style="margin-bottom: -5px; font-size: 18px; text-decoration: underline;">Course Evaluation</h4>
    <h4 style="margin-bottom: -5px; font-size: 18px; text-decoration: underline;">{{ $request['lesson']->title }}
        - {{ $request['lesson']->hours }} Hours</h4>
</div>


<p>Evaluation submitted: {{ optional($request['request'])->getRawOriginal('created_at') }}</p>
<h4>Student Info:</h4>
@if($request['user']->first_name == '')
    <span><b>Name: </b> {{ $request['user']->FullName }}</span><br/>
@else
    <span><b>First Name: </b> {{ $request['user']->first_name }}</span> <br/>
    @if($request['user']->middle_name != '')
        <span><b>Middle Name: </b> {{ $request['user']->middle_name }}</span> <br/>
    @endif
    <span><b>Last Name: </b> {{ $request['user']->last_name }}</span> <br/>
@endif

<span><b>Email: </b> {{ $request['user']->email }}</span> <br/>
<span><b>Age: </b> {{ $request['user']->age }}</span> <br/>
<span><b>State: </b> {{ $request['user']->state }}</span> <br/>
<hr>
<span><b>Enrolled At: </b> {{ \Carbon\Carbon::parse($request['userLesson']->enrolled_at)->format('M d, Y H:i:s') }}</span> <br/>
<span><b>Completed At: </b> {{ \Carbon\Carbon::parse($request['userLesson']->completed_at)->format('M d, Y H:i:s') }}</span> <br/>



<hr>

<table>

{{--    @foreach($request['request']->except('_token','course_id','evaluation_id') as $key => $val)--}}
    @foreach((json_decode($request['request']->details ?? '{}', true) ?: []) as $key => $val)
        @continue(in_array($key, ['_token','course_id','evaluation_id','year']))
        <tr>
            <td>{{ str_replace('_',' ',$key) }}</td>
            <td>{{ is_array($val) ? implode(', ', $val) : str_replace('_',' ',(string) $val) }}</td>
        </tr>
    @endforeach
</table>

<hr>
<b>End of email</b>


