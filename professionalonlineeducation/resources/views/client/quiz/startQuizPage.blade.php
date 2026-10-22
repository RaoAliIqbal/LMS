@extends('layouts.web')


@section('content')


    <section class="page_sidepadding page_sidepadding_home">
        <div class="dashboard_page">

            <div class="dashboard_row QuizRow">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="quizDetails">
                                <h1>{{ $quiz->title }}</h1>
                                {!! $quiz->description !!}
                                @if($isQuizAllowed)
                                    <a href="{{ route('chapterTakeQuiz',['id'=>$id,'chapterId'=>$chapterId, 'year'=>$year]) }}" class="btn btn-primary startQuizBtn">Start Test</a>
                                @else
                                    <p class="quizMsg">You need to spend minimum 55 minutes on {{ $course->title }} before starting Quiz.
                                        <br>
                                        <a href="{{ route('myCourseChapterLearning',['id'=>$id,'chapterId'=>$chapterId, 'year'=>$year]) }}">Start Learning</a>
                                    </p>
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
    <style>
        .startQuizBtn {margin-top: 50px;}

        .quizDetails {
            text-align: center;
        }
        .quizDetails h1 {
            color: #000;
            text-align: center;
        }
        p.quizMsg {
            font-weight: bold;
            background: #ffd78d;
            padding: 20px;
            margin-top: 100px;
        }
    </style>
@endpush
