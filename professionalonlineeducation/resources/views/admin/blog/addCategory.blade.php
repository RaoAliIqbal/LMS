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
                            {!! Form::open(['method'=>'POST','class'=>'validate','files'=>true,'action'=>['BlogController@categoriesSave']]) !!}
                           @if($pageData['type'] == 'edit')
                                <input type="hidden" value="{{ $category->id }}" name="id">
                            @endif
                            <div class="box-body profileView">
                                <div class="form-row">
                                    <div class="form-group col-sm-12">
                                        <label>Category Name*</label>
                                        {{ Helper::input('category_name','Category Name',isset($category->category_name)?$category->category_name:'',true,['id'=>'category_name'],null) }}

                                    </div>

                                </div>
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
