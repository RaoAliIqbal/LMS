@extends('layouts.web')

@section('content')
<div class="container">
    <section class="page_sidepadding page_sidepadding_home">
        <div class="dashboard_page">
            <div class="dashboard_row">
                <div class="row">
                    <div class="col-lg-12">
                        @include('layouts.partials.alertBox')
                        <div class="">
                            <div class="row courseTitleCon">
                                <div class="col-md-12">
                                    <h4>{{ $chapter->title }}</h4>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="ChapterDescription">
                                        {!! $chapter->description !!}
                                        <a href="{{ route('myCourseChapterLearning',['id'=>$course->id,'chapterId'=>$chapter->id, 'year'=>$year]) }}" class="btn btn-primary centerBtn StartBtn">Start</a>
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
    <style>

        .courseTitleCon {
            text-align: center;
            background: #6ba546;
            color: #fff;
            border-radius: 5px;
            margin: 20px 0;
        }
        .centerBtn {
            margin: auto;
            display: table;
        }
        .StartBtn{
            background: #6ba546;
        }
        .StartBtn:hover,.StartBtn:active {
            background: #528531;
            border: #528531;
        }

        .StartBtn {
            background: #528531 !important;
            border: #528531 !important;
        }

    </style>
@endpush
