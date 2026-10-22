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

                              @if(count($certificates))
                                  @foreach($certificates as $certificate)
                                      @if(isset($certificate->quiz))
                                    <div class="col-md-12 ">
                                        <div class="certificateBox">
                                            <div class="title">
                                                <h3>{{ $certificate->quiz->title }}</h3>
                                            </div>
                                            <a href="{{ route('client_getCertificate',$certificate->quiz->id) }}">Download Certificate</a>
                                        </div>

                                    </div>
                                        @endif
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