<script src="{{ url('courseAssets/js/jquery.js') }}" type="text/javascript"></script>
<script src="{{ url('courseAssets/js/scrollbar.js') }}" type="text/javascript"></script>

<script type="text/javascript">
    /* $(document).ready(function(){
        $(window).on('load', function () {
               var hash = window.location.hash.slice(1)
               var hash = window.location.hash.substring(1)
               console.log(hash);

                if(hash){
                    console.log($("#chapt"+hash).text());
                    $("#chapt"+hash).trigger("click");
                    $("#chapt"+hash).click();

                }
         });
     });   */

    $(document).ready(function(){
        var hash = window.location.hash.slice(1);
        var hash = window.location.hash.substring(1);
        console.log(hash);
        if(hash){
            console.log($("#chapt"+hash).text());
            $("#chapt"+hash).trigger("click");
            $("#chapt"+hash).addClass("active");
            //$("#chapt"+hash).click();
            var chapter=$("#chapt"+hash).attr("chapter");
            var topic=$("#chapt"+hash).attr("topic");
            scrolltolesson(chapter,topic);
        }
        id = $(".course_content_rownew.show").attr('data-id');
        scrolltolesson(id,0);
    });

    /*jQuery.each(jQuery('.menu-item'), function() {
        if($(this).hasClass('menu-item-has-children')){
        jQuery(this).addClass('no-link');
        }
    });*/
    function scrolltolesson(chapter,topic){
        //alert("chapter "+chapter+" Topic "+topic+"");
        $('html, body').animate({
            scrollTop: $("#les"+chapter).offset().top
        }, 800);
        $(".show").removeClass("show");
        $("#les"+chapter).addClass("show");
        $(".scrolltolesson").removeClass("active");
        $("#chapt_"+chapter).addClass("active");
        // $("#les"+chapter+'_'+topic).animate({
        //          scrollTop: 1000
        //        }, 2000);
    }
    function nextchapter(chap,lesson){

        $("#chapt"+chap+'_'+lesson).trigger("click");
        scrolltolesson(chap,lesson);

//        $(".scrolltolesson.active").closest('li').next().find('a').trigger("click");
    }
    function prevchapter(chap,lesson){
        $("#chapt"+chap+'_'+lesson).trigger("click");
        scrolltolesson(chap,lesson);
    }
    jQuery(".course_sidebar_lst ul li.menu-has-children").prepend('<div class="caretbox"><i class="fa fa-angle-down"></i></div>'),
        jQuery(".course_sidebar_lst ul li.menu-has-children > a").click(function() {
            return jQuery(this).parent().toggleClass("expanding"), jQuery(this).siblings(".sub-menu").slideToggle(), !1});
    // jQuery(".course_sidebar_lst ul li.menu-has-children.no-link > a").click(function() {
    // return jQuery(this).parent().toggleClass("expanding"), jQuery(this).siblings(".sub-menu").slideToggle(), !1});

    $(function(){
        $('.course_sidebar_lst ul li a').click(function () {
            $('.course_sidebar_lst ul li a').removeClass('active');
            $(this).addClass('active');
            var chapter=$(this).attr("chapter");
            var topic=$(this).attr("topic");

            scrolltolesson(chapter,topic);
        });
    });
    jQuery('.scrollbar-inner').scrollbar();

    $(".course_content_arow").click(function(){
        $('.course_pg').toggleClass("course_content_width");
    });

</script>
@stack('scripts')
