(function ($) {

    $.fn.webApp_grid = function (options) {

        var settings = $.extend({
            height          : '100%',
            autowidth       : true,
            pager           : true,
            toppager        : true,
            subGrid         : false,
            subGridUrl      : '',
            subGridModel    : [],
            actionNavOptions: null
        }, options);

        var grid = this;

        this.jqGrid({
            url               : settings.jsonUrl,
            datatype          : "json",
            height            : settings.height,
            autowidth         : settings.autowidth,
            colModel          : settings.colModel,
            rowNum            : settings.rowsPerPage,
            rowList           : [30, 50, 100],
            loadonce          : false,
            mtype             : "POST",
            pager             : settings.pager,
            toppager          : settings.toppager,
            sortname          : settings.sortName,
            sortorder         : settings.sortOrder,
            viewrecords       : true,
            multiselect       : true,
            guiStyle          : "bootstrap",
            iconSet           : "fontAwesome",
            subGrid           : settings.subGrid,
            subGridRowExpanded: function (subgrid_id, row_id) {
                var sg_tableID = subgrid_id + "_t";
                jQuery("#" + subgrid_id).html("<table id='" + sg_tableID + "' class'scroll'></table>");
                jQuery("#" + sg_tableID).jqGrid({
                    url      : settings.subGridUrl + '?id=' + encodeURIComponent(row_id),
                    datatype : "json",
                    mtype    : 'POST',
                    colModel : settings.subGridModel,
                    height   : '100%',
                    autowidth: true,
                    rowNum   : 10,
                    idPrefix : "s_" + row_id + "_"
                });
            },
            actionsNavOptions : settings.actionNavOptions
        });

        if (settings.filterToolbar)
            this.jqGrid('filterToolbar', {stringResult: false, searchOnEnter: true, autosearch: true});

        $(window).on("resize", function () {
            var newWidth = grid.closest(".ui-jqgrid").parent().width();
            grid.jqGrid("setGridWidth", newWidth, true);
        });

        return this;

    };

    $.webApp_modal = function (message, title, buttons, size) {

        if (title == null)
            title = 'Mensaje';

        if (buttons == null)
            buttons = [{text: 'Aceptar', close: true}];

        var dialog      = $("#myModal");
        var htmlButtons = '';

        var n = 0;
        buttons.forEach(function (b) {
            if (!('close' in b))
                b.close = true;
            htmlButtons += '<button type="button" class="btn ' + (n == 0 ? 'btn-primary' : 'btn-secondary') + '"' + (b.close ? ' data-dismiss="modal"' : '') + ' id="modal-button-' + n + '">' + b.text + '</button>';
            n++;
        });

        if (dialog.length == 0) {

            var sizeHtml;
            if (size == 'L')
                sizeHtml = ' modal-lg';
            else if (size == 'S')
                sizeHtml = ' modal-sm';
            else
                sizeHtml = '';

            var html =
                    '<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-hidden="true">' +
                    '<div class="modal-dialog' + sizeHtml + '" role="document">' +
                    '<div class="modal-content">' +
                    '<div class="modal-header">' +
                    '<h5 class="modal-title">' + title + '</h5>' +
                    '</div>' +
                    '<div class="modal-body">' + message + '</div>' +
                    '<div class="modal-footer">' + htmlButtons + '</div>' +
                    '</div>' +
                    '</div>' +
                    '</div>';

            $("body").append(html);

            dialog = $("#myModal");

        } else {

            $(".modal-title", dialog).html(title);
            $(".modal-body", dialog).html(message);
            $(".modal-footer", dialog).html(htmlButtons);

            $(".modal-dialog", dialog).removeClass('modal-lg');
            $(".modal-dialog", dialog).removeClass('modal-sm');

            if (size == 'L')
                $(".modal-dialog", dialog).addClass('modal-lg');
            else if (size == 'S')
                $(".modal-dialog", dialog).addClass('modal-sm');

        }

        if (htmlButtons == '')
            $(".modal-footer", dialog).hide();
        else
            $(".modal-footer", dialog).show();

        n = 0;
        buttons.forEach(function (b) {
            if (b.eventFunction)
                $("#modal-button-" + n).click(b.eventFunction);
            n++;
        });

        dialog.modal();

    };

}(jQuery));
