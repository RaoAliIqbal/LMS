@extends('layouts.web')

@section('content')
    <div class="container">
        <style>
            .course_info h3 { font-size:13.5px; margin-bottom:2px; }
            .course_info h3 a { font-size:13.5px; }
            .cour_small_details { font-size:11px; margin-bottom:2px; }
            .cour_small_details li { padding:1px 0; }
            .row.small_space_btm { border-bottom:1px solid #f0f0f0; padding:7px 0; margin-bottom:0; }
            .u_update_btn.btn { font-size:11px; padding:4px 10px; }
            .badge-pill { font-size:10px; padding:3px 8px; margin-bottom:6px; }
        </style>
        <section class="page_sidepadding page_sidepadding_home">
            <div class="dashboard_page">
                <div class="dashboard_row">
                    <div class="row">
                        @include('layouts.partials.dashboardNav')
                        <div class="col-lg-10">
                            @include('layouts.partials.alertBox')
                            <div class="useraccount_div">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h4>My Courses</h4>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        @php
                                            $courseRows = isset($courses) ? collect($courses) : collect();
                                        @endphp

                                        @forelse ($courseRows as $course)
                                            @php
                                                $c = $course->courseWithTrashed ?? $course->course ?? null;
                                                $courseYear = \Carbon\Carbon::parse($course->created_at ?? $course->enrolled_at ?? now())->year;
                                                $enrolledLabel = $course->enrolled_at ?? $course->created_at ?? null;
                                            @endphp
                                            <div class="row small_space_btm">
                                                <div class="col-sm-8">
                                                    <div class="course_info">
                                                        <h3>
                                                            @if ($c)
                                                                <a href="{{ route('client_Course', ['id' => $c->id, 'year' => $courseYear]) }}">{{ $c->title }}</a>
                                                            @else
                                                                <span>Course #{{ $course->lesson_id ?? '?' }}</span>
                                                            @endif
                                                        </h3>
                                                        <ul class="cour_small_details">
                                                            @if ($enrolledLabel)
                                                                <li>Purchased
                                                                    <span>{{ \Carbon\Carbon::parse($enrolledLabel)->format('M d, Y') }}</span>
                                                                </li>
                                                            @endif
                                                            @if (!empty($course->completed_at))
                                                                <li>Passed on
                                                                    <span>{{ \Carbon\Carbon::parse($course->completed_at)->format('M d, Y') }}</span>
                                                                </li>
                                                                <li>Credits Awarded
                                                                    <span>{{ optional($c)->hours }}</span>
                                                                </li>
                                                            @elseif ($c)
                                                                <li>Credits <span>{{ $c->hours }}</span></li>
                                                            @endif
                                                        </ul><br>
                                                        @if (!empty($course->completed_at))
                                                            <span class="badge badge-pill badge-success"
                                                                style="background-color:#6ba546; margin-bottom:10px">This
                                                                course has been completed</span><br />
                                                        @endif
                                                        @if ($c)
                                                            <a href="{{ route('client_Course', ['id' => $c->id, 'year' => $courseYear]) }}"
                                                                class="u_update_btn btn">
                                                                {{ !empty($course->completed_at) ? 'Completed' : 'View Details' }}
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-sm-4 make_right_btn">
                                                    @if ($c && !empty($course->completed_at))
                                                        @if (!empty($c->categories) && isset($c->categories[0]) && in_array($c->categories[0]->category_id, config('settings.CeCategories', [])))
                                                            <a href="{{ route('generateCertificate', ['id' => $course->lesson_id, 'year' => $courseYear]) }}"
                                                                class="u_update_btn">Download certificate</a>
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>
                                        @empty
                                            <p class="text-muted">You have not enrolled in any courses yet.
                                                <a href="{{ route('coursesPage') }}">Browse courses</a>
                                            </p>
                                        @endforelse
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
    </section>
    </div>
@endsection

@push('scripts')
@endpush