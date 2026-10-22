
<script src="/assets/js/lib.js"></script>
<script src="/assets/js/functions.js"></script>
<script>
    $("ul.side_navs li.parent").click(function() {
        $(this).toggleClass('rotate_me');
        $(this).children('ul.submenu_item').slideToggle(300);
    });
</script>