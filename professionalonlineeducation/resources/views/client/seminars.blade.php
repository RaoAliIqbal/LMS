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
                                    <h4>My Live Events</h4>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    @if(count($seminars))
                                        <ul class="userCourses">
                                            @foreach($seminars as $seminar)
                                                <li style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px; padding:12px 0; border-bottom:1px solid #eee;">
                                                    <span>
                                                        <a href="{{ route('client_event', $seminar->id) }}">
                                                            {{ $seminar->course_title }}
                                                        </a>
                                                        @if($seminar->start_date)
                                                            <small class="text-muted" style="margin-left:8px;">
                                                                {{ \Carbon\Carbon::parse($seminar->start_date)->format('M d, Y') }}
                                                            </small>
                                                        @endif
                                                    </span>
                                                    <span>
                                                        @if($seminar->pivot->completed_at)
                                                            <a href="{{ route('seminarCertificate', $seminar->id) }}"
                                                               class="btn btn-success btn-sm"
                                                               target="_blank">
                                                                <i class="fa fa-download"></i> Download Certificate
                                                            </a>
                                                        @else
                                                            <span class="badge" style="background:#f0ad4e; color:#fff; padding:5px 10px; border-radius:4px; font-size:11px;">
                                                                Certificate Pending
                                                            </span>
                                                        @endif
                                                    </span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <p>No live events found. <a href="{{ route('eventsPage') }}">Browse upcoming events.</a></p>
                                    @endif
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
