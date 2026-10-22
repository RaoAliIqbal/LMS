<div class="header" style="text-align: center">
    <h4 style="margin-bottom: -5px; font-size: 18px; text-decoration: underline;">Course Completion</h4>
    <h4 style="margin-bottom: -5px; font-size: 18px; text-decoration: underline;">{{ $data['lesson']->title }} - {{ $data['lesson']->hours }} Hours</h4>
</div>
<div>
    <h4 style="margin-bottom: -5px; font-size: 18px; text-decoration: underline;">Student Information</h4>
    <ul style="list-style: none; padding: 0">
        <li>Student Name: {{ $data['user']->FullName }}</li>
        <li>Student ID: {{ $data['user']->id*500 }}</li>
        @if($data['user']->category != null)
        <li>Profession: {{ $data['user']->category->title }}</li>
        @endif
        <li>Email: {{ $data['user']->email }}</li>
        <li>Mailing Address: {{ $data['user']->address }}{{ !empty($data['user']->suite) ? ', '.$data['user']->suite : '' }}</li>
        <li>City: {{ $data['user']->city }}</li>
        <li>State: {{ $data['user']->state }}</li>
        <li>Zipcode: {{ $data['user']->zipcode }}</li>
        <li>CE Hours Awarded: {{ $data['lesson']->hours }}</li>
{{--        <li>Password: {{ $data['user']->real_password }}</li>--}}
        <li>Test Score: {{ ($data['totalPercent'] == 0)?'75':$data['totalPercent'] }}%</li>
    </ul>
</div>
<div>
    @if(count($data['userLessonChapters']))
                @php $total = 0 @endphp
                @foreach($data['userLessonChapters'] as $chapter)
                    @php $total = $total+$chapter->time_spent; @endphp
                @endforeach
    @endif

    <ul style="list-style: none; padding: 0">
        <li>Date Started: {{ \Carbon\Carbon::parse($data['userLesson']->enrolled_at)->format('M d, Y') }}</li>
        <li>Date Completed: {{ \Carbon\Carbon::parse($data['userLesson']->completed_at)->format('M d, Y') }}</li>
        <li>Total Hrs: {{ $total/60 }}</li>
    </ul>
</div>

@if(count($data['user']->linceses))
<div>
    <h4 style="margin-bottom: -5px; font-size: 18px; text-decoration: underline;">License Information</h4>
    <ul style="list-style: none; padding: 0">
        @foreach($data['user']->linceses as $lincense)
        <li>License:  {{ $lincense->licence_no }} License State:  {{ $data['user']->state }}</li>
        @endforeach
    </ul>
</div>
@endif

@if(count($data['lesson']->approvals))
<div>
    <h4 style="margin-bottom: -5px; font-size: 18px; text-decoration: underline;">Course Approvals</h4>
    <ul style="list-style: none; padding: 0">
        @foreach($data['lesson']->approvals as $approval)
            @if(\Carbon\Carbon::parse($approval->end_date) > \Carbon\Carbon::now() )
        <li>{{ $approval->approval->approval }} Approval Number:  {{ $approval->approval_no }} </li>
            @endif
        @endforeach
    </ul>
</div>
@endif

@if(isset($data['user']->clinic) && $data['user']->clinic != null)
<div>
    <h4 style="margin-bottom: -5px; font-size: 18px; text-decoration: underline;">Office Information</h4>
    <ul style="list-style: none; padding: 0">
        <li>Clinic Name:  {{ $data['user']->clinic->clinic_name }} </li>
        <li>Doctor Name:  {{ $data['user']->clinic->doctor_name }} </li>
        <li>Address:  {{ $data['user']->clinic->address }} </li>
        <li>City:  {{ $data['user']->clinic->city }} </li>
        <li>State:  {{ $data['user']->clinic->state }} </li>
        <li>Zip Code:  {{ $data['user']->clinic->zipcode }} </li>
    </ul>
</div>
@endif


