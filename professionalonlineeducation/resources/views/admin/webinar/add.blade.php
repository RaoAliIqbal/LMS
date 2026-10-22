@extends('admin.layouts.app')

@section('title')
{{ $pageData['name'] }}
@endsection
@section('content')
    <div class="content-main " id="content-main">
        <!-- ############ Main START-->
        <div class="padding">
            <div class="row">
                <div class="col-md-12">
                    @include('layouts.partials.alertBox')
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">



                        <div class="box">
                            <div class="box-header">
                                <h2>{{ $pageData['name']  }}</h2>
                            </div>
                            {!! Form::open(['method'=>'POST','class'=>'validate','files'=>true,'action'=>['WebinarController@save']]) !!}
                           @if($pageData['type'] == 'edit')
                                <input type="hidden" value="{{ $webinar->id }}" name="id">
                            @endif
                            <div class="box-body profileView">
                                <div class="form-row">
                                    <div class="form-group col-sm-12">
                                        <label>Title*</label>
                                        {{ Helper::input('title','Title',isset($webinar->title)?$webinar->title:'',true,['id'=>'title'],null) }}

                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-sm-12">
                                        <label>Youtube Like*</label>
                                        {{ Helper::input('link','Youtube Like',isset($webinar->link)?$webinar->link:'',true,['id'=>'title'],null) }}

                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-sm-12">
                                        <label>Thumbnail*</label>
                                        <input type="file"  name="thumbnail_image" class="form-control">
                                    </div>
                                </div>
                                {{--{{ dd($webinar) }}--}}
                                @if(isset($webinar->thumbnail))

                                    <div class="form-row">
                                        <div class="form-group col-sm-12">
                                            <img src="{{ url($webinar->thumbnail) }}" width="150" alt="">
                                        </div>
                                    </div>
                                @endif

                                <div class="text-right">
                                    {{ Form::submit('Save',['class'=>'btn btn-primary cstm_btn']) }}

                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>

                </div>

            </div>

            <br>


        </div>
        <!-- ############ Main END-->
    </div>


@endsection
