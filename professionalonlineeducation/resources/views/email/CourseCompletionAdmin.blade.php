<div class="header" style="text-align: center">
    <h4 style="margin-bottom: -5px; font-size: 18px; text-decoration: underline;">Course Completion</h4>
    <h4 style="margin-bottom: -5px; font-size: 18px; text-decoration: underline;">{{ $data['lesson']->title }}
        - {{ $data['lesson']->hours }} Hours</h4>
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
        <li>Test Score: {{ $data['totalPercent'] === null ? 'Not recorded' : round($data['totalPercent'], 2).'%' }}</li>
        <li>Course ID: {{ $data['lesson']->title_dev }} | Enrollment: {{ $data['userLesson']->id }}</li>
        <li>Time values shown as stored by the application ({{ config('app.timezone') }}).</li>
        <li>CE Hours Awarded - {{ $data['lesson']->hours }}</li>
    </ul>
</div>
<div>
    <ul style="list-style: none; padding: 0">
        <li>Date Started: {{ \Carbon\Carbon::parse($data['userLesson']->enrolled_at)->format('M d, Y H:i:s') }}</li>
        <li>Date Completed: {{ \Carbon\Carbon::parse($data['userLesson']->completed_at)->format('M d, Y H:i:s') }}</li>
    </ul>
</div>

@if(count($data['user']->linceses))
    <div>
        <h4 style="margin-bottom: -5px; font-size: 18px; text-decoration: underline;">License Information</h4>
        <ul style="list-style: none; padding: 0">
            @foreach($data['user']->linceses as $lincense)
                <li>License: {{ $lincense->licence_no }} License State: {{ $data['user']->state }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if(count($data['lesson']->approvals    ))
    @php 
        $approval_no = [];
    @endphp
    <div>
        <h4 style="margin-bottom: -5px; font-size: 18px; text-decoration: underline;">Course Approvals</h4>
        <ul style="list-style: none; padding: 0">
       
            @foreach($data['lesson']->approvals as $approval)
                @php
                
                $courseApprovalStates = $approval->States()->pluck('state_id')->toArray();
                $userState = Helper::getStateIDbyName($data['user']->state);
             
                @endphp
                {{-- @if(in_array($userState,$courseApprovalStates) && \Carbon\Carbon::parse($approval->end_date) > \Carbon\Carbon::now() )   --}}
                {{-- @if(in_array($userState,$courseApprovalStates))   --}}
                @if(in_array($userState,$courseApprovalStates) 
                && (!in_array($approval->approval_no, $approval_no)) 
                &&  \Helper::checkApprovalValidity($data['userLesson']->completed_at, $approval->end_date) 
                && \Helper::checkApprovalValidity2($data['userLesson']->completed_at, $approval->start_date)
                // && $approval->Categories()->where('category_id',$data['user']->category_id)->first()
                )  
                    @php 
                        $approval_no[] = $approval->approval_no; 
                    @endphp
                    <li>{{ $approval->approval->approval }} Approval Number: {{ $approval->approval_no }} </li>
                @endif
            @endforeach
        </ul>
    </div>
@endif

@if(isset($data['user']->clinic) && $data['user']->clinic != null)
<div>
    <h4 style="margin-bottom: -5px; font-size: 18px; text-decoration: underline;">Office Information</h4>
    <ul style="list-style: none; padding: 0">
        <li>Clinic Name: {{ $data['user']->clinic->clinic_name }} </li>
        <li>Doctor Name: {{ $data['user']->clinic->doctor_name }} </li>
        <li>Address: {{ $data['user']->clinic->address }} </li>
        <li>City: {{ $data['user']->clinic->city }} </li>
        <li>State: {{ $data['user']->clinic->state }} </li>
        <li>Zip Code: {{ $data['user']->clinic->zipcode }} </li>
    </ul>
</div>
@endif
@if(count($data['userLessonChapters']))
    <div>
        <h4 style="margin-bottom: -5px; font-size: 18px; text-decoration: underline;">Hours per Module and Section</h4>
        <ul style="list-style: none; padding: 0">
            @php $total = 0 @endphp
            @foreach($data['userLessonChapters'] as $chapter)
                @php $total = $total+$chapter->time_spent; @endphp
                <li>Chapter: {{ optional($chapter->chapter)->title ?? 'Archived chapter #'.$chapter->chapter_id }}, Time: {{ $chapter->time_spent }} Mins</li>
            @endforeach
        </ul>
        <p>Test duration: not recorded separately.</p>

        <p>Total Study Time: 
            <?php
            $totalSeconds = $total * 60;

            $hours = floor($totalSeconds / 3600);
            $remainingSeconds = $totalSeconds % 3600;
            $minutes = floor($remainingSeconds / 60);
            $seconds = $remainingSeconds % 60;
            ?>
            {{ $hours }} hours {{ $minutes }} minutes {{ $seconds }} seconds

            {{-- {{ \Carbon\CarbonInterval::seconds($total * 60)->format('%h hours %i minutes %s seconds') }} --}}

            {{-- {{ Carbon\CarbonInterval::seconds($total*60)->cascade()->forHumans() }}  --}}
            {{-- {{ Carbon\CarbonInterval::minutes($total*60)->forHumans(['short' => true]) }} --}}
            {{-- {{ \Carbon\CarbonInterval::minutes($total)->format('%h hours %i minutes') }} --}}
            {{-- {{ gmdate("H:i:s", $total)}} --}}

        </p>
    </div>
@endif


 
@if(!count($data['userLessonChapters']))
<p>Study time: no recorded chapter activity for this enrollment window.</p>
@endif
<h4>Recorded Test Attempts</h4>
<table border="1" cellpadding="6" cellspacing="0">
<tr><th>Test</th><th>Score</th><th>Recorded At</th></tr>
@forelse($data['userQuizzes'] as $attempt)
<tr><td>{{ optional($attempt->quiz)->title ?? 'Quiz #'.$attempt->quiz_id }}</td><td>{{ is_numeric($attempt->user_percentage) ? $attempt->user_percentage.'%' : 'Not recorded' }}</td><td>{{ $attempt->getRawOriginal('created_at') }}</td></tr>
@empty
<tr><td colspan="3">No recorded test attempts for this enrollment window.</td></tr>
@endforelse
</table>
