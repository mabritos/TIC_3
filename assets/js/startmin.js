$(function() {

    $('#side-menu').metisMenu();

    $("#contract").click(function(e){
        e.preventDefault();
        var w = $("#wrapper");
        if (w.hasClass("contracted")) {
            w.removeClass("contracted");
            setCookie('sidebar-contracted', '0');
        }else {
            w.addClass("contracted");
            setCookie('sidebar-contracted', '1');
        }
        setLinkContent();
        $(window).trigger('resize');
    });

    setLinkContent();

    function setLinkContent() {
        if ($("#wrapper").hasClass("contracted"))
            $("#contract").html('<i class="fa fa-arrow-circle-o-right fa-fw"></i> <span>Cerrar menu</span>');
        else
            $("#contract").html('<i class="fa fa-arrow-circle-o-left fa-fw"></i> <span>Cerrar menu</span>');
    }

    function setCookie(name, value) {
        var d           = new Date();
        d.setTime(d.getTime() + (365 * 24 * 60 * 60 * 1000));
        document.cookie = name + "=" + value + "; expires=" + d.toUTCString() + "; path=/";
    }

});

//Loads the correct sidebar on window load,
//collapses the sidebar on window resize.
// Sets the min-height of #page-wrapper to window size
$(function() {
    $(window).bind("load resize", function() {
        topOffset = 50;
        width = (this.window.innerWidth > 0) ? this.window.innerWidth : this.screen.width;
        if (width < 768) {
            $('div.navbar-collapse').addClass('collapse');
            topOffset = 100; // 2-row-menu
        } else {
            $('div.navbar-collapse').removeClass('collapse');
        }

        height = ((this.window.innerHeight > 0) ? this.window.innerHeight : this.screen.height) - 1;
        height = height - topOffset;
        if (height < 1) height = 1;
        if (height > topOffset) {
            $("#page-wrapper").css("min-height", (height) + "px");
        }
    });

    var url = window.location;
    var element = $('ul.nav a').filter(function() {
        return this.href == url /*|| url.href.indexOf(this.href) == 0*/;
    }).addClass('active').parent().parent().addClass('in').parent();
    if (element.is('li')) {
        element.addClass('active');
    }
});
