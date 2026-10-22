@extends('admin.layouts.app')

@section('title') Analytics & Reporting @endsection

@section('content')
<div class="content-main" id="content-main">
<div class="padding">

{{-- ── Header ── --}}
<div class="row mb-3 align-items-center">
    <div class="col-md-7">
        <h4 class="mb-0"><i class="fa fa-bar-chart text-primary"></i> Analytics & Reporting</h4>
        <p class="text-muted mb-0">Platform-wide performance metrics for {{ $year }}</p>
    </div>
    <div class="col-md-5 text-right">
        <form method="GET" action="{{ route('admin_analytics') }}" class="d-inline-flex align-items-center">
            <label class="mr-2 mb-0 text-muted small">Year:</label>
            <select name="year" class="form-control form-control-sm mr-2" style="width:100px;" onchange="this.form.submit()">
                @foreach($years as $y)
                    <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
                @endforeach
            </select>
        </form>
    </div>
</div>

{{-- ── KPI Cards ── --}}
<div class="row mb-4">
    <div class="col-md-2 col-sm-4 col-6 mb-3">
        <div class="box p-3 text-center h-100" style="border-top:3px solid #4a89dc;">
            <div style="font-size:28px;font-weight:700;color:#4a89dc;">{{ number_format($totalStudents) }}</div>
            <div class="text-muted small">Total Students</div>
        </div>
    </div>
    <div class="col-md-2 col-sm-4 col-6 mb-3">
        <div class="box p-3 text-center h-100" style="border-top:3px solid #37bc9b;">
            <div style="font-size:28px;font-weight:700;color:#37bc9b;">{{ number_format($newStudentsYear) }}</div>
            <div class="text-muted small">New Students {{ $year }}</div>
        </div>
    </div>
    <div class="col-md-2 col-sm-4 col-6 mb-3">
        <div class="box p-3 text-center h-100" style="border-top:3px solid #967adc;">
            <div style="font-size:28px;font-weight:700;color:#967adc;">{{ number_format($totalCourses) }}</div>
            <div class="text-muted small">Total Courses</div>
        </div>
    </div>
    <div class="col-md-2 col-sm-4 col-6 mb-3">
        <div class="box p-3 text-center h-100" style="border-top:3px solid #f6bb42;">
            <div style="font-size:28px;font-weight:700;color:#f6bb42;">{{ number_format($totalEnrollments) }}</div>
            <div class="text-muted small">Enrollments {{ $year }}</div>
        </div>
    </div>
    <div class="col-md-2 col-sm-4 col-6 mb-3">
        <div class="box p-3 text-center h-100" style="border-top:3px solid #e9573f;">
            <div style="font-size:28px;font-weight:700;color:#e9573f;">{{ number_format($totalCompleted) }}</div>
            <div class="text-muted small">Completions {{ $year }}</div>
        </div>
    </div>
    <div class="col-md-2 col-sm-4 col-6 mb-3">
        <div class="box p-3 text-center h-100" style="border-top:3px solid #3bafda;">
            <div style="font-size:28px;font-weight:700;color:#3bafda;">{{ $overallCompletion }}%</div>
            <div class="text-muted small">Completion Rate</div>
        </div>
    </div>
</div>

{{-- ── Enrollment & Completion Chart ── --}}
<div class="row mb-4">
    <div class="col-md-8 mb-3">
        <div class="box h-100">
            <div class="box-header"><strong><i class="fa fa-line-chart text-primary"></i> Monthly Enrollments vs Completions — {{ $year }}</strong></div>
            <div class="box-body">
                <canvas id="enrollmentChart" height="100"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="box h-100">
            <div class="box-header"><strong><i class="fa fa-pie-chart text-success"></i> Overall Completion Rate</strong></div>
            <div class="box-body d-flex align-items-center justify-content-center">
                <canvas id="completionDonut" height="180" style="max-width:200px;"></canvas>
            </div>
        </div>
    </div>
</div>

{{-- ── Course Completion Rates ── --}}
<div class="row mb-4">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header d-flex justify-content-between align-items-center">
                <strong><i class="fa fa-graduation-cap text-warning"></i> Course Completion Rates — {{ $year }}</strong>
                <span class="text-muted small">{{ count($courseStats) }} courses with enrollments</span>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-sm table-hover" style="font-size:13px;">
                        <thead class="thead-light">
                            <tr>
                                <th>Course</th>
                                <th class="text-center">Hours</th>
                                <th class="text-center">Enrollments</th>
                                <th class="text-center">Completions</th>
                                <th style="min-width:160px;">Completion Rate</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($courseStats as $cs)
                            @php
                                $rate = $cs->completion_rate ?? 0;
                                $color = $rate >= 90 ? '#37bc9b' : ($rate >= 70 ? '#f6bb42' : '#e9573f');
                            @endphp
                            <tr>
                                <td>{{ $cs->title }}</td>
                                <td class="text-center">{{ $cs->hours }}</td>
                                <td class="text-center">{{ $cs->enrollments }}</td>
                                <td class="text-center">{{ $cs->completions }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="progress flex-grow-1 mr-2" style="height:8px;">
                                            <div class="progress-bar" style="width:{{ $rate }}%;background:{{ $color }};"></div>
                                        </div>
                                        <span style="min-width:38px;font-weight:600;color:{{ $color }};">{{ $rate }}%</span>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ── Time Spent + Quiz Performance ── --}}
<div class="row mb-4">
    {{-- Time Spent --}}
    <div class="col-md-6 mb-3">
        <div class="box h-100">
            <div class="box-header">
                <strong><i class="fa fa-clock-o text-info"></i> Time Spent Per Course — {{ $year }}</strong>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-sm table-hover" style="font-size:13px;">
                        <thead class="thead-light">
                            <tr>
                                <th>Course</th>
                                <th class="text-center">Required</th>
                                <th class="text-center">Avg/Chapter</th>
                                <th class="text-center">Students</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($timeStats as $ts)
                            <tr>
                                <td style="max-width:200px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;" title="{{ $ts->title }}">
                                    {{ Str::limit($ts->title, 40) }}
                                </td>
                                <td class="text-center">{{ $ts->hours }}h</td>
                                <td class="text-center">
                                    <span class="{{ $ts->avg_mins_per_chapter >= 55 ? 'text-success' : 'text-warning' }} font-weight-bold">
                                        {{ $ts->avg_mins_per_chapter }}m
                                    </span>
                                </td>
                                <td class="text-center">{{ $ts->students }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Quiz Performance --}}
    <div class="col-md-6 mb-3">
        <div class="box h-100">
            <div class="box-header">
                <strong><i class="fa fa-pencil-square-o text-danger"></i> Quiz Performance — {{ $year }}</strong>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-sm table-hover" style="font-size:13px;">
                        <thead class="thead-light">
                            <tr>
                                <th>Course</th>
                                <th class="text-center">Attempts</th>
                                <th class="text-center">Avg Score</th>
                                <th class="text-center">Pass Rate</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($quizStats as $qs)
                            @php $pr = $qs->pass_rate ?? 0; @endphp
                            <tr>
                                <td style="max-width:200px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;" title="{{ $qs->title }}">
                                    {{ Str::limit($qs->title, 40) }}
                                </td>
                                <td class="text-center">{{ number_format($qs->total_attempts) }}</td>
                                <td class="text-center font-weight-bold">{{ $qs->avg_score }}%</td>
                                <td class="text-center">
                                    <span class="badge badge-{{ $pr >= 90 ? 'success' : ($pr >= 75 ? 'warning' : 'danger') }}">
                                        {{ $pr }}%
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ── Top Students ── --}}
<div class="row mb-4">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header">
                <strong><i class="fa fa-trophy text-warning"></i> Top Students by Completions — {{ $year }}</strong>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-sm table-hover" style="font-size:13px;">
                        <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>Student</th>
                                <th>Email</th>
                                <th class="text-center">Courses Completed</th>
                                <th class="text-center">Avg Quiz Score</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topStudents as $i => $s)
                            <tr>
                                <td>
                                    @if($i === 0) 🥇
                                    @elseif($i === 1) 🥈
                                    @elseif($i === 2) 🥉
                                    @else {{ $i + 1 }}
                                    @endif
                                </td>
                                <td>{{ trim($s->first_name . ' ' . $s->last_name) ?: 'N/A' }}</td>
                                <td>{{ $s->email }}</td>
                                <td class="text-center font-weight-bold text-primary">{{ $s->courses_completed }}</td>
                                <td class="text-center">{{ $s->avg_quiz_score ?? '—' }}{{ $s->avg_quiz_score ? '%' : '' }}</td>
                                <td class="text-center">
                                    <a href="{{ route('admin_clientActivity', $s->id) }}" class="btn btn-xs btn-info">
                                        <i class="fa fa-bar-chart"></i> Activity
                                    </a>
                                    <a href="{{ route('admin_clientCourses', $s->id) }}" class="btn btn-xs btn-default">
                                        <i class="fa fa-book"></i> Courses
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

</div>{{-- end padding --}}
</div>{{-- end content-main --}}
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
const months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];

// ── Enrollment vs Completion Line Chart ──
new Chart(document.getElementById('enrollmentChart'), {
    type: 'bar',
    data: {
        labels: months,
        datasets: [
            {
                label: 'Enrollments',
                data: @json($monthlyData),
                backgroundColor: 'rgba(74,137,220,0.2)',
                borderColor: '#4a89dc',
                borderWidth: 2,
                type: 'bar'
            },
            {
                label: 'Completions',
                data: @json($monthlyCompData),
                borderColor: '#37bc9b',
                backgroundColor: 'rgba(55,188,155,0.1)',
                borderWidth: 2,
                type: 'line',
                fill: true,
                tension: 0.3,
                pointBackgroundColor: '#37bc9b'
            }
        ]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'top' } },
        scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
    }
});

// ── Completion Donut ──
new Chart(document.getElementById('completionDonut'), {
    type: 'doughnut',
    data: {
        labels: ['Completed', 'In Progress'],
        datasets: [{
            data: [{{ $totalCompleted }}, {{ max(0, $totalEnrollments - $totalCompleted) }}],
            backgroundColor: ['#37bc9b', '#e9e9e9'],
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        cutout: '70%',
        plugins: {
            legend: { position: 'bottom' },
            tooltip: { callbacks: {
                label: function(c) {
                    let total = c.dataset.data.reduce((a,b)=>a+b,0);
                    let pct = total > 0 ? Math.round(c.raw/total*100) : 0;
                    return c.label + ': ' + c.raw + ' (' + pct + '%)';
                }
            }}
        }
    }
});
</script>
@endpush
