@extends('admin.layouts.app')

@section('title')
    Customers Licences
@endsection
@section('content')
    <div class="content-main " id="content-main">
        <!-- ############ Main START-->
        <div class="padding">
            <div class="row">
                <div class="col-md-12">


                        <div class="box">
                            <div class="box-header">
                                <h2>Customer's Licences</h2>
                            </div>
                            <div class="box-body">
                                <div class="table-responsive">
                                    <table id="datatable" class="table v-middle p-0 m-0 box" data-plugin="dataTable">
                                        <thead>
                                        <tr>
                                            <th>Licence #</th>
                                            <th>Renewal Date</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        @if(count($licences))
                                            @foreach($licences as $licence)
                                                <tr>
                                                    <td>{{ $licence->licence_no }}</td>
                                                    <td>{{ $licence->renewal_data }}</td>
                                                </tr>
                                            @endforeach
                                        @endif

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                </div>

            </div>

        </div>
        <!-- ############ Main END-->
    </div>


@endsection
