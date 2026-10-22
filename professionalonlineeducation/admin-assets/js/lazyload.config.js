// lazyload config
var MODULE_CONFIG = {
    chat: [
        '/assets/libs/list.js/dist/list.js',
        '/assets/libs/notie/dist/notie.min.js',
        '/assets/js/plugins/notie.js',
        '/assets/assets/js/app/chat.js'
    ],
    mail: [
        '/assets/libs/list.js/dist/list.js',
        '/assets/libs/notie/dist/notie.min.js',
        '/assets/js/plugins/notie.js',
        '/assets/js/app/mail.js'
    ],
    user: [
        '/assets/libs/list.js/dist/list.js',
        '/assets/libs/notie/dist/notie.min.js',
        '/assets/js/plugins/notie.js',
        '/assets/js/app/user.js'
    ],
    fullscreen: [
        '/assets/libs/jquery-fullscreen-plugin/jquery.fullscreen-min.js',
        '/assets/js/plugins/fullscreen.js'
    ],
    jscroll: [
        '/assets/libs/jscroll/jquery.jscroll.min.js'
    ],
    stick_in_parent: [
        '/assets/libs/sticky-kit/jquery.sticky-kit.min.js'
    ],
    scrollreveal: [
        '/assets/libs/scrollreveal/dist/scrollreveal.min.js',
        '/assets/js/plugins/jquery.scrollreveal.js'
    ],
    owlCarousel: [
        '/assets/libs/owl.carousel/dist/assets/owl.carousel.min.css',
        '/assets/libs/owl.carousel/dist/assets/owl.theme.css',
        '/assets/libs/owl.carousel/dist/owl.carousel.min.js'
    ],
    html5sortable: [
        '/assets/libs/html5sortable/dist/html.sortable.min.js',
        '/assets/js/plugins/jquery.html5sortable.js',
        '/assets/js/plugins/sortable.js'
    ],
    easyPieChart: [
        '/assets/libs/easy-pie-chart/dist/jquery.easypiechart.min.js'
    ],
    peity: [
        '/assets/libs/peity/jquery.peity.js',
        '/assets/js/plugins/jquery.peity.tooltip.js',
    ],
    chart: [
        '/assets/libs/moment/min/moment-with-locales.min.js',
        '/assets/libs/chart.js/dist/Chart.min.js',
        '/assets/js/plugins/jquery.chart.js',
        '/assets/js/plugins/chartjs.js'
    ],

    bootstrapTable: [
        '/assets/libs/bootstrap-table/dist/bootstrap-table.min.css',
        '/assets/libs/bootstrap-table/dist/bootstrap-table.min.js',
        '/assets/libs/bootstrap-table/dist/extensions/export/bootstrap-table-export.min.js',
        '/assets/libs/bootstrap-table/dist/extensions/mobile/bootstrap-table-mobile.min.js',
        '/assets/js/plugins/tableExport.min.js',
        '/assets/js/plugins/bootstrap-table.js'
    ],
    bootstrapWizard: [
        '/assets/libs/twitter-bootstrap-wizard/jquery.bootstrap.wizard.min.js'
    ],
    dropzone: [
        '/assets/libs/dropzone/dist/min/dropzone.min.js',
        '/assets/libs/dropzone/dist/min/dropzone.min.css'
    ],
    datetimepicker: [
        '/assets/libs/tempusdominus-bootstrap-4/build/css/tempusdominus-bootstrap-4.min.css',
        '/assets/libs/moment/min/moment-with-locales.min.js',
        '/assets/libs/tempusdominus-bootstrap-4/build/js/tempusdominus-bootstrap-4.min.js',
        '/assets/js/plugins/datetimepicker.js'
    ],
    datepicker: [
        "/assets/libs/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js",
        "/assets/libs/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css",
    ],
    fullCalendar: [
        '/assets/libs/moment/min/moment-with-locales.min.js',
        '/assets/libs/fullcalendar/dist/fullcalendar.min.js',
        '/assets/libs/fullcalendar/dist/fullcalendar.min.css',
        '/assets/js/plugins/fullcalendar.js'
    ],
    parsley: [
        '/assets/libs/parsleyjs/dist/parsley.min.js'
    ],
    select2: [
        '/assets/libs/select2/dist/css/select2.min.css',
        '/assets/libs/select2/dist/js/select2.min.js',
        '/assets/js/plugins/select2.js'
    ],
    summernote: [
        '/assets/libs/summernote/dist/summernote.css',
        '/assets/libs/summernote/dist/summernote-bs4.css',
        '/assets/libs/summernote/dist/summernote.min.js',
        '/assets/libs/summernote/dist/summernote-bs4.min.js'
    ],
    vectorMap: [
        '/assets/libs/jqvmap/dist/jqvmap.min.css',
        '/assets/libs/jqvmap/dist/jquery.vmap.js',
        '/assets/libs/jqvmap/dist/maps/jquery.vmap.world.js',
        '/assets/libs/jqvmap/dist/maps/jquery.vmap.usa.js',
        '/assets/libs/jqvmap/dist/maps/jquery.vmap.france.js',
        '/assets/js/plugins/jqvmap.js'
    ],
    waves: [
        '/assets/libs/node-waves/dist/waves.min.css',
        '/assets/libs/node-waves/dist/waves.min.js',
        '/assets/js/plugins/waves.js'
    ]
};

var MODULE_OPTION_CONFIG = {
    parsley: {
        errorClass: 'is-invalid',
        successClass: 'is-valid',
        errorsWrapper: '<ul class="list-unstyled text-sm mt-1 text-muted"></ul>'
    }
}
