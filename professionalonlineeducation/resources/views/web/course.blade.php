@extends('layouts.course')

@section('content')

    <section class="course_pg">
        <div class="course_sidebar d-none d-xl-block">
            <div class="scrollbar-inner">
                <div class="course_sidebar_headr">
                    <span class="hme_icon"><a href="{{ route('investorEducation') }}"><i class="fa fa-bars"></i></a></span>
                    <span class="hme_icon hme_icon01"><a href="{{ route('home_') }}"><i class="fa fa-home"></i></a></span>
                    <h2>{{ $lesson->title }}</h2>
                </div>
                <div class="course_sidebar_lst">
                    <ul>
                        @if(count($mainChapters))
                            @foreach($mainChapters as $mainChapter)
                        <li class="{{ ($mainChapter->have_child == '1')?'menu-has-children':'' }}">
                            <a class="scrolltolesson"
                               chapter="{{ $mainChapter->id }}"
                               topic="0"
                               id="chapt{{ $mainChapter->id }}_0"
                               href="javascript:void(0);">
                                <span>Chapter {{ $mainChapter->chapter_no }}</span> {{ $mainChapter->title }}
                            </a>
                           @if($mainChapter->have_child == '1')
                            <ul class="sub-menu">
                                @foreach($mainChapter->children as $child)
                                <li><a id="chapt{{ $mainChapter->id.'_'.$child->id }}"
                                       chapter="{{ $mainChapter->id }}"
                                       topic="{{ $child->id }}" href="javascript:void(0);">
                                        <span>Chapter {{ $child->chapter_no }}</span>
                                        {{ $child->title }}</a>
                                </li>
                                @endforeach
                            </ul>
                            @endif
                        </li>
                            @endforeach

                        @endif

                    </ul>
                </div>
            </div>
        </div>
        <div class="course_content">
            @if(count($mainChapters))
                @foreach($mainChapters as $chapter)
                    <div class="course_content_rownew {{ ($loop->index == 0)?'show':'' }}"
                         id="les{{ $chapter->id.'_0' }}"
                         data-id="{{ $chapter->id }}">
                        <div class="scrollbar-inner">
                            @if($loop->index != 0)
                            <div id="previous_chapter" class="course_btn" onclick="prevchapter()"><i class="fa fa-angle-up"></i> <span>Previous Chapter</span></div>
                            @endif

                            <div class="course_content_row">
                                <span class="course_content_arow"><i class="fa fa-angle-left"></i></span>
                                <div class="course_content_wrap">
                                    <h1><span>Chapter {{ $chapter->chapter_no }}</span>{{ $chapter->title }}</h1>
                                    {!! $chapter->description !!}
                                </div>
                            </div>
                            <div id="next_chapter" class="course_btn" {{ $chapter->chapter_no }} onclick="nextchapter({{ Helper::getNextChapter($chapter->chapter_no) }})">
                                <span>Next Chapter</span> <i class="fa fa-angle-down"></i></div>
                        </div>
                    </div>

                    @if($chapter->have_child == '1')
                        @foreach($chapter->children as $child)
                            <div class="course_content_rownew"
                                 id="les{{ $chapter->id.'_'.$child->id }}">
                                <div class="scrollbar-inner">
                                    @if($loop->index != 0)
                                        <div id="previous_chapter" class="course_btn" onclick="prevchapter()"><i class="fa fa-angle-up"></i> <span>Previous Chapter</span></div>
                                    @endif

                                    <div class="course_content_row">
                                        <span class="course_content_arow"><i class="fa fa-angle-left"></i></span>
                                        <div class="course_content_wrap">
                                            <h1><span>Chapter {{ $child->chapter_no }}</span>{{ $child->title }}</h1>
                                            {!! $child->description !!}
                                        </div>
                                    </div>
                                    <div id="next_chapter" class="course_btn" {{ $child->chapter_no }}  onclick="nextchapter({{ Helper::getNextChapter($child->chapter_no) }})">
                                        <span>Next Chapter</span> <i class="fa fa-angle-down"></i></div>
                                </div>
                            </div>
                        @endforeach
                    @endif


                @endforeach

            @endif

        </div>
    </section>

@endsection
