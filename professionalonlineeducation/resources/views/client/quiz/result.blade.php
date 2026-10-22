@extends('layouts.web')


@section('content')


    <section class="page_sidepadding page_sidepadding_home">
        <div class="dashboard_page ">
            <div class="container">
                <div class="dashboard_row result_page">

                    <h1>{{ $quiz->title }}</h1>
                    <div class="resultBox {{ $result }}">
                        <div class="per">{{ $per }}%</div>
                        @if($result == 'pass')
                            <div class="passMsg">
                                <h3>Congratulations</h3>
                                <a href="{{ route('client_getCertificate',$quiz->id) }}">Get Certificate</a>
                            </div>
                        @else
                            <div class="failMsg">
                                <h3>Better luck next time</h3>
                                <a href="{{ route('client_TakeQuiz',$quiz->id) }}">Retake Quiz</a>
                            </div>
                        @endif
                    </div>

                </div>
            </div>

        </div>
        </div>
    </section>

@endsection

@push('scripts')

@endpush
