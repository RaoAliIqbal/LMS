@extends('layouts.web')

@section('content')

    <section class="page_sidepadding page_sidepadding_home">
        <div class="dashboard_page">
            <h1>Quizzes</h1>
            <div class="dashboard_row">
                <div class="row">

                   @include('layouts.partials.dashboardNav')

                    <div class="col-lg-10">
                        @include('layouts.partials.alertBox')
                        <div class="useraccount_div">
                            <div class="row">

                              @if(count($quizzes))
                                  @foreach($quizzes as $quiz)
                                    <div class="col-md-4 ">
                                        <div class="quizBox">
                                            <h3>{{ $quiz->title }}</h3>
                                            <p>{!! $quiz->description !!}</p>
                                            <span><b>Passing Percentage </b> {{ $quiz->passing_percentage }}%</span>
                                            <a href="{{ route('client_TakeQuiz',$quiz->id) }}">Start Quiz</a>
                                        </div>

                                    </div>
                                    @endforeach
                                @else
                                  No Quiz Found.
                                @endif

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </section>

@endsection

@push('scripts')

@endpush