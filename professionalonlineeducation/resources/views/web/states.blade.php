@extends('layouts.web')

@section('title','Boost Your Career | Professional Online Education States')
@section('keywords','Professional Online Education States')
@section('description','Looking to become a licensed chiropractor in your state? Then look for Professional Online Education States wise trainings and boost your career today!')


@section('content')
    <section class="top_section_banner" style="background-image:url('{{ url("webAssets/images/banner-homepage.jpg") }}');">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="top_banner_titles">
                        <h3>States</h3>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <section class="states_section">

        <div class="container">
            <div class="shadow_box add_spaces">

                <div class="row">
                    <div class="col-sm-12">
                        <div class="small_section_titles">
                            <h4>Select Your State</h4>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <ul class="states_list">
                            @if(count($states))
                                @foreach($states as $state)
                                    <li><a href="{{ route('statesCategories',strtolower($state->slug)) }}">{{ $state->name }}</a></li>
                                @endforeach
                             @endif
                        </ul>
                    </div>
                </div>

            </div>
        </div>

    </section>


@endsection
