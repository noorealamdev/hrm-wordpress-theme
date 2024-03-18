(function($) {
    "use strict";

    $(document).ready(function() {

        // Mobile Menu Js Start
        $('#mobile-menu').meanmenu({
            meanMenuContainer: '.mobile-menu',
            meanScreenWidth: "991",
            meanExpand: ['<i class="far fa-plus"></i>'],
        });

        // Sidebar Toggle Js Start
        $(".offcanvas__close,.offcanvas__overlay").on("click", function(e) {
            e.preventDefault();
            $(".offcanvas__info").removeClass("info-open");
            $(".offcanvas__overlay").removeClass("overlay-open");
        });
        $(".sidebar__toggle").on("click", function(e) {
            e.preventDefault();
            $(".offcanvas__info").addClass("info-open");
            $(".offcanvas__overlay").addClass("overlay-open");
        });

        // Body Overlay Js Start
        $(".body-overlay").on("click", function(e) {
            e.preventDefault();
            $(".offcanvas__area").removeClass("offcanvas-opened");
            $(".df-search-area").removeClass("opened");
            $(".body-overlay").removeClass("opened");
        });

        // Nice Select
        $('selector').niceSelect();

        // Datatable
        new DataTable('#dataTable');
        new DataTable('#dataTableBank');
        new DataTable('#dataTableSalary');

        // Feather icons
        feather.replace();

        // Select2
        $('.select2').select2();

        // Fix page bottom white spaces while no content
        if($(window).height() > $("body").height()){
            $("footer").css({"position" : "fixed", "bottom" : "0" , "width" : "100%"});
        } else {
            $("footer").css("position", "relative");
        }


    }); // End Document Ready Function


})(jQuery); // End jQuery