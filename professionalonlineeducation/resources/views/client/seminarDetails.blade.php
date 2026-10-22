@extends('layouts.web')

@section('content')
<div class="container">
    <section class="page_sidepadding page_sidepadding_home">
        <div class="dashboard_page">
            <div class="dashboard_row">
                <div class="row">
                    <div class="col-lg-10">
                        @include('layouts.partials.alertBox')
                        <div class="useraccount_div">
                            <div class="row">
                                <div class="col-md-12">
                                    <h4>{{ $seminar->course_title }}</h4>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    {!! $seminar->description !!}
                                    <hr>
                                    <h3>Price: {{ $seminar->cost != '' ? $seminar->cost : '20.00' }}</h3>
                                    <h3>Max Students: {{ $seminar->max_students }}</h3>
                                    <h3>Start At: {{ \Carbon\Carbon::parse($seminar->start_date)->format('M d, Y') }} {{ $seminar->start_time ? \Carbon\Carbon::parse($seminar->start_time)->format('g:i A') : '' }}</h3>
                                    <h3>End At: {{ \Carbon\Carbon::parse($seminar->end_date)->format('M d, Y') }} {{ $seminar->end_time ? \Carbon\Carbon::parse($seminar->end_time)->format('g:i A') : '' }}</h3>

                                    @if($seminar->is_online)
                                        <div class="alert alert-info mt-3" style="border-left:4px solid #2c6fad;">
                                            <i class="fa fa-video-camera"></i>
                                            <strong>This is an Online / Hybrid class</strong>
                                        </div>
                                    @endif

                                    {{-- ── Zoom details: only visible to enrolled students ── --}}
                                    @auth
                                        @php
                                            $isEnrolled = auth()->user()->seminars->contains('id', $seminar->id);
                                            $classStarted = \Carbon\Carbon::parse($seminar->start_date . ' ' . ($seminar->start_time ?? '00:00:00'))->subHours(1)->isPast();
                                        @endphp

                                        @if($isEnrolled && $seminar->zoom_link)
                                            <div class="card mt-4" style="border:2px solid #2d8cff;border-radius:8px;">
                                                <div class="card-body" style="background:#f0f7ff;">
                                                    <h4 style="color:#2d8cff;">
                                                        <i class="fa fa-video-camera"></i> Join Live Class via Zoom
                                                    </h4>
                                                    <hr>
                                                    @if($seminar->zoom_meeting_id)
                                                    <p><strong>Meeting ID:</strong>
                                                        <span style="font-size:18px;letter-spacing:2px;font-family:monospace;">
                                                            {{ $seminar->zoom_meeting_id }}
                                                        </span>
                                                    </p>
                                                    @endif
                                                    @if($seminar->zoom_password)
                                                    <p><strong>Passcode:</strong>
                                                        <span style="font-size:18px;font-family:monospace;">
                                                            {{ $seminar->zoom_password }}
                                                        </span>
                                                    </p>
                                                    @endif
                                                    <p class="text-muted small">
                                                        <i class="fa fa-clock-o"></i>
                                                        Class starts: {{ \Carbon\Carbon::parse($seminar->start_date . ' ' . ($seminar->start_time ?? '00:00:00'))->format('M d, Y \a\t g:i A') }}
                                                    </p>
                                                    <a href="{{ $seminar->zoom_link }}"
                                                       target="_blank"
                                                       class="btn btn-lg btn-primary"
                                                       style="background:#2d8cff;border-color:#2d8cff;">
                                                        <i class="fa fa-video-camera"></i>
                                                        Join Zoom Meeting
                                                    </a>
                                                    <p class="text-muted small mt-2">
                                                        <i class="fa fa-lock"></i>
                                                        This link is private and only visible to enrolled students.
                                                    </p>
                                                </div>
                                            </div>
                                        @elseif($isEnrolled && $seminar->is_online && !$seminar->zoom_link)
                                            <div class="alert alert-warning mt-3">
                                                <i class="fa fa-clock-o"></i>
                                                You are enrolled. The Zoom link will appear here closer to the class date.
                                            </div>
                                        @elseif(!$isEnrolled && $seminar->is_online)
                                            <div class="alert alert-secondary mt-3">
                                                <i class="fa fa-lock"></i>
                                                Enroll to access the Zoom meeting link.
                                            </div>
                                        @endif
                                    @endauth

                                    @guest
                                        @if($seminar->is_online)
                                            <div class="alert alert-secondary mt-3">
                                                <i class="fa fa-lock"></i>
                                                <a href="{{ route('login') }}">Log in</a> and enroll to access the Zoom meeting link.
                                            </div>
                                        @endif
                                    @endguest
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
