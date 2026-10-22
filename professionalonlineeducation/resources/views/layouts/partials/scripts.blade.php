<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script type="text/javascript" src="{{ url('webAssets/js/scripts.js') }}"></script>
@php
    $mianPath = base_path('webAssets/js/mian.js');
    $mianV = file_exists($mianPath) ? (string) filemtime($mianPath) : '1';
@endphp
<script type="text/javascript" src="{{ url('webAssets/js/mian.js') }}?v={{ $mianV }}"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.1.0/css/font-awesome.min.css'>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js" integrity="sha512-rstIgDs0xPgmG6RX1Aba4KV5cWJbAMcvRCVmglpam9SoHZiUCyQVDdH2LPlxoHtrv17XWblE/V/PP+Tr04hbtA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script type="text/javascript" src="{{ url('webAssets/js/validation.js') }}"></script>

<script>
    var __webAuth = @json(auth()->check());
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    //$('#datepicker').datepicker({});
    $('.btnLoading').on('click', function () {
        let $this = $(this);
        $this.button('loading');
        let ActionType = $this.attr('data-type');
        if(ActionType == 'add-to-cart'){
            let courseId = $this.attr('data-course-id') || $this.data('courseId') || $this.data('courseid');
            let courseTitle = $this.attr('data-course-title') || $this.data('courseTitle') || $this.data('coursetitle') || 'Course';
            if (!courseId) {
                $this.button('reset');
                $("#CartAlert").html("<div class='alert alert-danger fade in'>Could not read course id. Please refresh the page.</div>");
                return;
            }
            $.ajax({
                url: "{{ route('addToCart') }}",
                method: 'POST',
                data: {courseId: courseId},
                success: function (res) {
                    $("#cartCount").html(res);
                    $this.button('reset');
                    let html = "<div class='alert alert-success  fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a> <b>"+courseTitle+"</b> added into your cart.</div>";
                    $("#CartAlert").html(html);

                },
                error: function (xhr) {
                    $this.button('reset');
                    var msg = (xhr && xhr.status === 422) ? 'This course could not be added. It may be invalid or unavailable.' : 'Could not add to cart. Please try again.';
                    $("#CartAlert").html("<div class='alert alert-danger fade in'>"+msg+"</div>");
                }
            });
        }
        if(ActionType == 'add-to-cart-seminar'){
            let seminarId = $this.attr('data-seminar-id') || $this.data('seminarId') || $this.data('seminarid');
            let seminarTitle = $this.attr('data-seminar-title') || $this.data('seminarTitle') || $this.data('seminartitle') || 'Live event';
            if (!seminarId) {
                $this.button('reset');
                $("#CartAlert").html("<div class='alert alert-danger fade in'>Could not read event id. Please refresh the page.</div>");
                return;
            }
            if (!__webAuth) {
                window.location.href = "{{ url('/event/add-to-cart-intent') }}/" + encodeURIComponent(seminarId);
                return;
            }
            $.ajax({
                url: "{{ route('addToCartSeminar') }}",
                method: 'POST',
                data: {seminarId: seminarId},
                success: function (res) {
                    $("#cartCount").html(res);
                    $this.button('reset');
                    let html = "<div class='alert alert-success  fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a> <b>"+seminarTitle+"</b> added to your cart. <a href='{{ route('addToCartPage') }}'>View cart &amp; checkout</a></div>";
                    $("#CartAlert").html(html);

                },
                error: function () {
                    $this.button('reset');
                    $("#CartAlert").html("<div class='alert alert-danger fade in'>Could not add this event to your cart. Please try again.</div>");
                }
            });
        }


        setTimeout(function () {
            $this.button('reset');
        }, 8000);
    });
</script>
@stack('scripts')
