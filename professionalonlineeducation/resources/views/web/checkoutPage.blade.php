@extends('layouts.web')

@section('content')
    <section class="top_section_banner" style="background-image:url('{{ url("webAssets/images/banner-homepage.jpg") }}');">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="top_banner_titles">
                        <h3>Checkout</h3>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="category_section blue_bg_section section_tb_pads">

        <div class="container">

            <div class="row">
                <div class="col-sm-12">
                    <div class="small_section_titles white_txt inc_size_title">
                        <h4>Checkout</h4>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="checkout_section">

            <div class="container">
                @php
                    $hasCourses = isset($courses) && count($courses) > 0;
                    $hasSeminars = isset($seminars) && count($seminars) > 0;
                @endphp
                <form class="validate" action="@if($hasSeminars && ! $hasCourses){{ route('seminarCartEnroll') }}@elseif($hasCourses && ! $hasSeminars){{ route('cartEnroll') }}@elseif($hasCourses && $hasSeminars){{ route('unifiedCartEnroll') }}@else{{ route('cartEnroll') }}@endif" method="post">
                    @csrf
                <div class="row">
                    <div class="block_title add_some_new_space" data-aos="fade-right">
                        <h2>Your order</h2>
                        @include('layouts.partials.alertBox')
                    </div>
                    <div class="col-md-6">
                        @if($hasCourses || $hasSeminars)

                                @foreach($courses as $course)

{{--                                    <li> <br> ${{ $course->price }}</li>--}}



                                      <div class="row">
                                          <div class="white_bg_block">
                                          <div class="col-sm-3">
                                              <div class="course_img">
                                                  <img src="{{ url($course->image) }}"/>
                                              </div>
                                          </div>
                                          <div class="col-sm-9">
                                              <div class="course_item_detail">
                                                  <h3>{{ $course->title }}</h3>
                                                  <p class="offer currency-price" data-usd="{{ Helper::usdDataAmount($course->price) }}"><span class="curr-symbol">$</span><span class="curr-amount">{{ Helper::formatUsd($course->price) }}</span></p>
                                              </div>
                                          </div>
                                          </div>
                                      </div>


                                @endforeach

                                @foreach($seminars as $seminar)
                                      <div class="row" style="margin-top: 1rem;">
                                          <div class="white_bg_block">
                                          <div class="col-sm-3">
                                              <div class="course_img" style="background:#f0f0f0;min-height:120px;display:flex;align-items:center;justify-content:center;">
                                                  <span class="text-muted" style="padding:8px;text-align:center;">Live<br>event</span>
                                              </div>
                                          </div>
                                          <div class="col-sm-9">
                                              <div class="course_item_detail">
                                                  <h3>{{ $seminar->course_title }}</h3>
                                                  <p class="offer currency-price" data-usd="{{ Helper::usdDataAmount($seminar->price_for_display) }}"><span class="curr-symbol">$</span><span class="curr-amount">{{ Helper::formatUsd($seminar->price_for_display) }}</span></p>
                                              </div>
                                          </div>
                                          </div>
                                      </div>
                                @endforeach
                                @else
                                    <p>Your Cart is empty</p>
                                @endif

                        <div class="row">
                                      <div class="col-md-8">
                                          <div class="couponContainer">
                                              <input type="hidden" id="total" value="{{ $total }}">
                                              <input type="text"  name="code" id="discountCode" class="form-control" placeholder="Discount Code" value="">
                                              <button type="button" id="applyDiscount" class="btn-primary btn">Apply Discount</button>
                                          </div>
                                      </div>
                                      <div class="col-md-4" id="summeryContainer">
                                          @include('web.partials.summeryBlock',['total'=>$total,'appliedDiscount'=>false, 'newTotal'=>false])
                                      </div>
                                  </div>
                    </div>
                    <div class="col-md-6">
                        @php
                            $months = array(1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'May', 6 => 'Jun', 7 => 'Jul', 8 => 'Aug', 9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dec');
                        @endphp

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
                                <div class="form-group position-relative">
                                    <label for="">Billing Address <span class="label_start">*</span></label>
                                    <div class="form-group sameAsUserContainer">
                                        <label for="">Same as office</label>
                                        <input type="checkbox" class="" id="sameAsUser">
                                    </div>
                                    
                                    <textarea required class="form-control" name="address" data-officeAddress="{{ (auth()->user() && auth()->user()->clinic) ? auth()->user()->clinic->address : '' }}" data-userAddress="{{ (auth()->user())?auth()->user()->address:'' }}">{{ old('address', auth()->check() ? (auth()->user()->address ?? '') : '') }}</textarea>
                                    @error('address')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12" style="display:none;">
                                <input type="hidden" name="suite" value="{{ (auth()->user())?auth()->user()->suite:'' }}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">City</label>
                                    <input type="text" class="form-control" required name="city" value="{{ old('city', auth()->check() ? (auth()->user()->city ?? '') : '') }}" data-officeCity="{{ (auth()->user() && auth()->user()->clinic) ? auth()->user()->clinic->city : '' }}" data-userCity="{{ (auth()->user())?auth()->user()->city:'' }}">
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
                                <select data-officeState="{{ (auth()->user() && auth()->user()->clinic) ? auth()->user()->clinic->state : '' }}" data-userState="{{ (auth()->user())?auth()->user()->state:'' }}" name="state_id" id="state_id" class="form-control">
                                    @php $selState = old('state_id', auth()->check() ? (auth()->user()->state ?? '') : ''); @endphp
                                    @foreach($states as $state)
                                        <option value="{{ $state->name }}" {{ $selState === $state->name ? 'selected' : '' }}>{{ $state->name }}</option>
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
                                    <input type="text" class="form-control" required name="zipcode" value="{{ old('zipcode', auth()->check() ? (auth()->user()->zipcode ?? '') : '') }}" data-officeZipcode="{{ (auth()->user() && auth()->user()->clinic) ? auth()->user()->clinic->zipcode : '' }}" data-userZipcode="{{ (auth()->user())?auth()->user()->zipcode:'' }}">
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
                                <div class="form-group position-relative">
                                    <label for="">Billing Email <span class="label_start">*</span></label>
                                    <div class="form-group sameAsUserContainer">
                                        <label for="">Same as Student</label>
                                        <input type="checkbox" class="" id="sameAsStudent">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="billing_email">Billing Email for receipt <small class="text-muted">(Authorize.net receipt &amp; seminar info)</small></label>
                                    <input type="email" class="form-control" required name="email" id="billing_email" value="{{ old('email', auth()->check() ? (auth()->user()->email ?? '') : '') }}">
                                    <small class="text-muted">Enter the doctor or office email if different from the student login.</small>
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
                                    @error('owner')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        <div class="row">
                                <div class="form-group col-md-12" id="card-number-field">
                                    <label for="cardNumber">Card Number</label>
                                    <input type="text" class="form-control payment-field" id="cardNumber" name="cardNumber" value="{{ old('cardNumber') }}" required>
                                    @error('cardNumber')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
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
                                    @error('expiration-month')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                    <select class="form-control payment-field" id="expiration-year" name="expiration-year"  style="float: left; width: 100px;">

                                        @for($i = date('Y'); $i <= (date('Y') + 15); $i++)
                                            <option value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                    </select>
                                    @error('expiration-year')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="form-group CVV col-md-4">
                                    <label for="cvv">CVV</label>
                                    <input type="number" class="form-control payment-field" id="cvv" name="cvv" value="{{ old('cvv') }}" required>
                                    @error('cvv')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>{{-- end #paymentSection --}}


                        @if($ageVerification)
                            <div class="row">
                                <div class="form-group col-md-12">

                                    <label for="ageCheckBox">
                                        <input type="checkbox" id="ageCheckBox" name="age"  required>
                                        I certify I am  18 years of age or older And I certify I have or will have my high school diploma or equivalent GED</label>

                                </div>
                            </div>
                        @endif

                            <br/>
                            <input type="submit" class="btn btn-primary btn-lg" value="Submit" />

{{--                        <a href="{{ route('cartEnroll') }}" class="btn btn-primary btn-lg">Submit</a>--}}
                    </div>
                </div>
                </form>
            </div>

    </section>


    <style>
        .sameAsUserContainer {
            float: right;
        }
        ul.checkoutSummery {
            float: right;
            text-align: right;
        }
        .coursebox {
            background-color: #0000004d;
            min-height: 450px;
        }
        .cart_block {
            display: none;
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
        let billing_mail = "{{ (auth()->user())?auth()->user()->email:'' }}";


        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // applyPromotion();

        // function applyPromotion(){
        //     let code = localStorage.getItem('code');
        //     if(code !== null){
        //         applyDiscount(code);
        //         $("#discountCode").val(code);
        //         localStorage.removeItem("code");
        //     }
        // }

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
                    code: code,
                    total: total,
                    courses: @json($courses->pluck('id')->values()->all())
                },
                success: function (res) {
                    $("#summeryContainer").html(res);
                    $.unblockUI();
                    // Re-apply currency conversion after summary refreshes
                    if (typeof applyConversion === 'function') {
                        var currSym = localStorage.getItem('selectedSymbol') || '$';
                        var currCode = localStorage.getItem('selectedCurr') || 'USD';
                        applyConversion(currCode, currSym);
                    }
                    var newTotal = parseFloat($("#newTotalValue").data("total"));
                    if (newTotal <= 0) {
                        $("#paymentSection").hide();
                        $("#paymentSection .payment-field").prop("required", false);
                    } else {
                        $("#paymentSection").show();
                        $("#paymentSection .payment-field[id='owner'], #paymentSection .payment-field[id='cardNumber'], #paymentSection .payment-field[id='cvv']").prop("required", true);
                    }
                },
                error: function () {
                    $.unblockUI();
                    alert('Could not apply discount. Please try again or call 1-888-900-3382.');
                }
            });
        }


        $("#sameAsUser").change(function (){
            let checkbox = $("#sameAsUser");
            let address = $("textarea[name='address']");
            let city = $("input[name='city']");
            let zipcode = $("input[name='zipcode']");
            let state = $("#state_id");
            let suite = $('#suite');

            if(checkbox.is(':checked')){
                // Use OFFICE address (from clinic), not student/home address
                let oldAddress = address.attr('data-officeAddress');
                let oldCity = city.attr('data-officeCity');
                let oldZipCode = zipcode.attr('data-officeZipcode');
                let oldState = state.attr('data-officeState');
                let oldSuite = suite.attr('data-officeSuite') || suite.attr('data-suite');
                address.val(oldAddress || '');
                city.val(oldCity || '');
                zipcode.val(oldZipCode || '');
                suite.val(oldSuite || '');
                if (oldState) {
                    $("#state_id option[value='"+oldState+"']").prop('selected', true);
                }
            }else{
                address.val(address.attr('data-userAddress') || '');
                city.val(city.attr('data-userCity') || '');
                zipcode.val(zipcode.attr('data-userZipcode') || '');
                suite.val('');
                var uState = state.attr('data-userState');
                if (uState) {
                    $("#state_id option").filter(function () { return $(this).val() === uState; }).prop('selected', true);
                } else {
                    $("#state_id option:first").prop("selected", true);
                }
            }

        });

        $('#sameAsStudent').click(function(){
            if($(this).is(':checked')){
                $('input[name="email"]').val(billing_mail);
            }else{
                $('input[name="email"]').val("");
            }
        });

        $('#billing_email').on('input', function () {
            if ($(this).val() && billing_mail && $(this).val() !== billing_mail) {
                $('#sameAsStudent').prop('checked', false);
            }
        });
    </script>
@endpush
