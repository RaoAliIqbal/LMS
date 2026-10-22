@extends('layouts.web')

@section('content')
<div class="container">
    <section class="page_sidepadding page_sidepadding_home">
        <div class="dashboard_page">
            <div class="dashboard_row">
                <div class="row">
                    <div class="col-lg-10">
                        @include('layouts.partials.alertBox')
                        <div class="useraccount_div">
                            <div class="row">
                                <div class="col-md-10">
                                    <h4>Your Licenses</h4>
                                </div>
                                <div class="col-md-2">
                                    <button class="btn btn-primary" id="AddNew">Add New</button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    @if(count($user->linceses))
                                        <ul class="licenseList">
                                            @foreach($user->linceses as $lincese)
                                                <li>
                                                    <span>{{ $lincese->licence_no }}</span>
                                                    <span>
                                                        <button class="btn btn-primary"
                                                                onclick="edit(this)"
                                                                data-id="{{ $lincese->id }}"
                                                                data-number="{{ $lincese->licence_no }}"
                                                                data-renewal="{{ $lincese->renewal_data }}">Edit</button>
                                                        <button class="btn btn-danger btnBgRed"
                                                                onclick="deleteItem(this)"
                                                                data-id="{{ $lincese->id }}">Delete</button>
                                                    </span>
                                                </li>
                                            @endforeach
                                        </ul>

                                    @endif
                                        <br>
                                        <br>
                                        <br>
                                        <a href="{{ route('client_home') }}" class="u_update_btn">Back to dashboard</a>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <form id="form" action="{{ route('client_licenseSave') }}" method="post">
                <input type="hidden" name="id" id="id">
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title">License Details</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="">License Number</label>
                        <input type="text" id="number" required name="license_no" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="">Renewal Date</label>
                        <input type="date" id="date" required name="renewal_data" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
            </form>
        </div>

    </div>
</div>
@endsection

@push('scripts')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        ul.licenseList li {
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ededed;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .btnBgRed {
            /* background: #d43f3a !important; */
            background: linear-gradient(184deg, #d43f3a 0%, #b52621 35%);
        }
        ul.licenseList li span {font-weight: bold;}
    </style>
<script>
    function edit(t) {
        let ele = $(t);
        $("#id").val(ele.attr('data-id'));
        $("#number").val(ele.attr('data-number'));
        $("#date").val(ele.attr('data-renewal'));
        $("#myModal").modal('show');
    }
    $("#AddNew").click(function (){
        $('#form').trigger("reset");
        $("#myModal").modal('show');

    });
    function deleteItem(t){
        let ele = $(t);
        let url = "/user/dashboard/license/"+ele.data('id')+"/delete";
        Swal.fire({
            title: 'Are you sure you want to delete this License?',
            showCancelButton: true,
            confirmButtonText: 'Yes Delete License',
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                location.assign(url);
                // Swal.fire('Deleted', '', 'success')
            }
        })
    }


</script>
@endpush
