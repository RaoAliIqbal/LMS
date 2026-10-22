@extends('layouts.web')

@section('content')
<div class="container">

    <section class="user_dashboard">
        <div class="payment_history_block">
            <div class="container">

                <div class="row">
                    <div class="col-sm-12">
                        <h2>Payment History</h2>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <table id="history" class="table table-hover table-condensed">
                            <thead>
                            <tr>
                                <th>Invoice #</th>
                                <th>Date</th>
                                <th>Description</th>
                                <th>Amount</th>
                            </tr>
                            </thead>
                            <tbody>
                                @if(count($paymentLogs))
                                    @foreach($paymentLogs as $paymentLog)
                                        <tr>
                                            <td>{{ $paymentLog['log']->transaction_id }}</td>
                                            <td>{{ $paymentLog['log']->created_at }}</td>
                                            <td>
                                                @foreach($paymentLog['courses'] as $course)
                                                    {{ $course->title }} <br/>
                                                @endforeach
                                                @foreach($paymentLog['seminars'] as $seminar)
                                                    {{ $seminar->paymentHistoryDescription() }} <br/>
                                                @endforeach
                                            </td>
                                            <td>${{ $paymentLog['log']->amount }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>


    </section>

</div>



@endsection

@push('scripts')
    <style>
        a.u_update_btn {
            background: #6ea947;
            color: #fff;
            padding: 6px 15px;
            border-radius: 15px;
            font-size: 16px;
        }

        a.u_update_btn:hover {
            background: #487629;
        }
        ul.u_info.licenseList {
            width: 45%;
            float: left;
        }
        ul.liveSeminarDashboardUl li {
            color: #0f75bc;
            font-size: 16px;
            margin-bottom: 25px;
            cursor: pointer;
        }
    </style>
    <!--$("#profileImg").change(function(){-->
    <!--    alert('5');-->
    <!--});-->
    <script>
        function profileChange(){
        $("#profileForm").submit();
    }
    </script>
@endpush
