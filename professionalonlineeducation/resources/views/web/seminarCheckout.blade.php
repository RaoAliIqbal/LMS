@extends('layouts.web')

@section('content')
    @php $total = str_replace('$','',$seminar->cost) @endphp
<style>
    .courseDetailsPage {
        margin-top: 60px;
        margin-bottom: 60px;
    }
</style>

    <section class="states_section courseDetailsPage">

        <div class="container">
            <div class="shadow_box add_spaces">
                <form action="{{ route('client_seminarEnroll',$seminar->id) }}" method="post">
                    @csrf
                <div class="row">
                    <div class="col-sm-6">
                        <div class="small_section_titles">
                            <h4>{{ $seminar->course_title }}</h4>
                            {!! $seminar->description !!}
                            <hr>

                            <div class="row">
                                <div class="col-md-8">
                                    <div class="couponContainer">
                                        <input type="hidden" id="total" value="{{ $total }}">
                                        <input type="text"  name="code" id="discountCode" class="form-control" placeholder="Discount Code">
                                        <button type="button" id="applyDiscount" class="btn-primary btn">Apply Discount</button>
                                    </div>
                                </div>
                                <div class="col-md-4" id="summeryContainer">
                                    @include('web.partials.summeryBlock',['total'=>$total,'appliedDiscount'=>false, 'newTotal'=>false])
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="col-sm-6">
						<ul class="checkout-meta">
						<li>
							<label>Price:</label> {{ $seminar->cost != '' ? $seminar->cost : '20.00' }}</h3>
						</li>
                        <li>
							<label>Max Students:</label> {{ $seminar->max_students }}
						</li>
						<li>
                            <label>Date:</label> {{ \Carbon\Carbon::parse($seminar->start_date)->format('M d') }} - {{ \Carbon\Carbon::parse($seminar->end_date)->format('M d') }}, {{ \Carbon\Carbon::parse($seminar->start_date)->format('Y') }}
						</li>
						<li>
                            <label>Time:</label> {{ \Carbon\Carbon::parse($seminar->start_time)->format('g:i A') }} : {{ \Carbon\Carbon::parse($seminar->end_time)->format('g:i A') }}</h3>
						</li>
                        @php
                            $months = array(1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'May', 6 => 'Jun', 7 => 'Jul', 8 => 'Aug', 9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dec');
                        @endphp
					</ul>

                        <div class="row">
                            <div class="col-md-12">
                                @include('layouts.partials.alertBox')
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">First Name</label>
                                    <input type="text" class="form-control" required name="first_name" value="{{ (auth()->user())?auth()->user()->first_name:'' }}">
                                    @error('first_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Last Name</label>
                                    <input type="text" class="form-control" required name="last_name" value="{{ (auth()->user())?auth()->user()->last_name:'' }}">
                                    @error('last_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Billing Address <span class="label_start">*</span></label>
                                    <textarea required class="form-control" name="address">{{ old('address') }}</textarea>
                                    @error('address')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">City</label>
                                    <input type="text" class="form-control" required name="city" value="{{ (auth()->user())?auth()->user()->city:'' }}">
                                    @error('city')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="">State</label>
                                <select name="state_id" class="form-control">
                                    @foreach($states as $state)
                                        <option {{ (auth()->user())?(($state->name == auth()->user()->state)?'selected':''):'' }} value="{{ $state->name }}">{{ $state->name }}</option>
                                    @endforeach
                                </select>
                                @error('state_id')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Zipcode</label>
                                    <input type="text" class="form-control" required name="zipcode" value="{{ (auth()->user())?auth()->user()->zipcode:'' }}">
                                    @error('zipcode')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Phone Number</label>
                                    <input type="text" class="form-control" required name="phone" value="{{ (auth()->user())?auth()->user()->phone:'' }}">
                                    @error('phone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Billing Email for receipt</label>
                                    <input type="text" class="form-control" required name="email" value="{{ (auth()->user())?auth()->user()->email:'' }}">
                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div id="paymentSection">
                        <div class="row">
                                <div class="form-group owner col-md-12">
                                    <label for="owner">Name On Card</label>
                                    <input type="text" class="form-control payment-field" id="owner" name="owner" value="{{ old('owner') }}" required>
                                </div>
                            </div>
                     <div class="row">
                                <div class="form-group col-md-12" id="card-number-field">
                                    <label for="cardNumber">Card Number</label>
                                    <input type="text" class="form-control payment-field" id="cardNumber" name="cardNumber" value="{{ old('cardNumber') }}" required>
                                </div>
                            </div>
                     <div class="row">
                                <div class="form-group col-md-8" id="expiration-date">
                                    <label>Expiration Date</label><br/>
                                    <select class="form-control payment-field" id="expiration-month" name="expiration-month" style="float: left; width: 100px; margin-right: 10px;">
                                        @foreach($months as $k=>$v)
                                            <option value="{{ $k }}" {{ old('expiration-month') == $k ? 'selected' : '' }}>{{ $v }}</option>
                                        @endforeach
                                    </select>
                                    <select class="form-control payment-field" id="expiration-year" name="expiration-year"  style="float: left; width: 100px;">

                                        @for($i = date('Y'); $i <= (date('Y') + 15); $i++)
                                            <option value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="form-group CVV col-md-4">
                                    <label for="cvv">CVV</label>
                                    <input type="number" class="form-control payment-field" id="cvv" name="cvv" value="{{ old('cvv') }}" required>
                                </div>
                            </div>
                        </div>{{-- end #paymentSection --}}
                     <br/>
                     <input type="submit" class="btn btn-primary btn-lg" value="Submit" />
                    </div>
                </div>
                </form>

            </div>
        </div>

    </section>
<style>
    .course_details.seminarDetails h3 {
        height: 4px;
    }
    .couponContainer input {
        width: 70%;
        border-radius: 0;
    }
    .couponContainer {
        display: flex;
    }
    .couponContainer button {
        border-radius: 0;
    }
</style>

    {{-- Currency Converter Widget (checkout only) --}}
    @include('layouts.partials.currency-widget')

@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.blockUI/2.70/jquery.blockUI.min.js" integrity="sha512-eYSzo+20ajZMRsjxB6L7eyqo5kuXuS2+wEbbOkpaur+sA2shQameiJiWEzCIDwJqaB0a4a6tCuEvCOBHUg3Skg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        applyPromotion();

        function applyPromotion(){
            let code = localStorage.getItem('code');
            if(code !== null){
                applyDiscount(code);
                $("#discountCode").val(code);
                localStorage.removeItem("code");
            }
        }

        $("#applyDiscount").click(function (){
            let code = $("#discountCode").val();
            applyDiscount(code);
            $("#discountCode").val(code);
        });
        function applyDiscount(code){

            let total = $("#total").val();
            $.blockUI({ css: {
                    border: 'none',
                    padding: '15px',
                    backgroundColor: '#000',
                    '-webkit-border-radius': '10px',
                    '-moz-border-radius': '10px',
                    opacity: .5,
                    color: '#fff'
                } });
            $.ajax({
                url: "{{ route('applyDiscount') }}",
                method: 'POST',
                data: {
                    code:code,
                    total:total
                },
                success: function (res) {
                    $("#summeryContainer").html(res);
                    $.unblockUI();
                    var newTotal = parseFloat($("#newTotalValue").data("total"));
                    if (newTotal <= 0) {
                        $("#paymentSection").hide();
                        $("#paymentSection .payment-field").prop("required", false);
                    } else {
                        $("#paymentSection").show();
                        $("#paymentSection .payment-field[id='owner'], #paymentSection .payment-field[id='cardNumber'], #paymentSection .payment-field[id='cvv']").prop("required", true);
                    }
                }
            });
        }


    </script>
@endpush
