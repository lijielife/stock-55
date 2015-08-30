$(function() {

    function displayResult(item) {
        $('.alert').show().html('You selected <strong>' + item.value + '</strong>: <strong>' + item.text + '</strong>');
    }

    $.get($getAllSymbol, function (data) {
        $("#symbol").typeahead({source: data, onSelect: displayResult});
    }, 'json');



    $.get($getAllBroker, function (datas) {
        $.each(datas, function(index, value){
            $li = $("#brokerMenu").children("li:first").hide().clone(true).show();
            $li.children("a:first").attr("value", value.ID).html(value.BROKER_NAME);
            $("#brokerMenu").append($li);
        });
//        $("#symbol").typeahead({source: data, onSelect: displayResult});
    }, 'json');


    $(function() {

        var headers = $('#accordion .accordion-header');
        var contentAreas = $('#accordion .ui-accordion-content');
        var expandLink = $('.accordion-expand-all');

// add the accordion functionality
        headers.click(function() {
            var panel = $(this).next();
            var isOpen = panel.is(':visible');

            // open or close as necessary
            panel[isOpen ? 'slideUp' : 'slideDown']()
                    // trigger the correct custom event
                    .trigger(isOpen ? 'hide' : 'show');
            // stop the link from causing a pagescroll.
            return false;
        });

// hook up the expand/collapse all
        expandLink.click(function() {
            var isAllOpen = $(this).data('isAllOpen');

            contentAreas[isAllOpen ? 'hide' : 'show']()
                    .trigger(isAllOpen ? 'hide' : 'show');
        });

// when panels open or close, check to see if they're all open
        contentAreas.on({
            // whenever we open a panel, check to see if they're all open
            // if all open, swap the button to collapser
            show: function() {
                var isAllOpen = !contentAreas.is(':hidden');
                if (isAllOpen) {
                    expandLink.data('isAllOpen', true)
                            .children("i").removeClass().addClass("glyphicon glyphicon-minus");
                }
                $(this).prev().removeClass();
                $(this).prev().addClass("accordion-header ui-accordion-header ui-helper-reset ui-state-default ui-accordion-icons ui-accordion-header-active ui-state-active ui-corner-top");   
            },
            // whenever we close a panel, check to see if they're all open
            // if not all open, swap the button to expander
            hide: function() {
                var isAllOpen = !contentAreas.is(':hidden');
                if (!isAllOpen) {
                    
                    expandLink.data('isAllOpen', false)
                            .children("i").removeClass().addClass("glyphicon glyphicon-plus");
                }
                $(this).prev().removeClass();
                $(this).prev().addClass("accordion-header ui-accordion-header ui-helper-reset ui-state-default ui-accordion-icons ui-corner-all");   
            }
        }).slideUp().hide();
    });
});