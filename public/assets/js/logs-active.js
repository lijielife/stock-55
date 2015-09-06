$(function() {

//    function displayResult(item) {
//        $('.alert').show().html('You selected <strong>' + item.value + '</strong>: <strong>' + item.text + '</strong>');
//    }

    $.get($getAllSymbol, function (data) {
        $("#symbol").typeahead({source: data});
    }, 'json');



    $.get($getAllBroker, function (datas) {
        $.each(datas, function(index, value){
            $li = $("#brokerMenu").children("li:first").clone(true).show();
            $li.children("a:first").attr("value", value.ID).html(value.BROKER_NAME);
            $("#brokerMenu").append($li);
        });
//        $("#symbol").typeahead({source: data, onSelect: displayResult});
    }, 'json');
    
    $("#brokerMenu li").click(function(){
        $(this).parent().prev().html($(this).find("a").html() + '<span class="caret"></span>');
    });

    var $liSelected = "0";
    $("#formSearch").submit(function(){
        var $symbol = $("#symbol").val();
        var $symbolInput = $("#subSymbol").val($symbol);
        var $brokerInput = $("#subBroker").val($liSelected);
       
//            $('#formSearch').append($($symbolInput)).append($($brokerInput));
            
        return true;
        
//        var $symbol = $("#symbol").val();
//        var $symbol = $("#symbol").val();
        
    });

    $(function() {

        var headers = $('#accordion .accordion-header');
        var subHeaders = $('#accordion .accordion-sub-header');
        var contentAreas = $('#accordion .ui-accordion-content');
        var expandLink = $('.accordion-expand-all');
        var selected = $(this); 
// add the accordion functionality
        headers.click(function() {
            var panel = $(this).next();
            selected = panel; 
            var isOpen = panel.is(':visible');

            // open or close as necessary
            panel[isOpen ? 'slideUp' : 'slideDown']()
                    // trigger the correct custom event
                    .trigger(isOpen ? 'hide' : 'show');
            // stop the link from causing a pagescroll.
            return false;
        });
        
        subHeaders.click(function() {
            var panel = $(this).next();
            selected = panel; 
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
                
                if($(this).is(selected)){
                    
                    var $prev = $(this).prev();



                    var $isSub = $prev.hasClass( "ui-accordion-sub" );


                    $prev.removeClass();
                    if($isSub){
                        $prev.addClass("accordion-header ui-accordion-header ui-helper-reset ui-state-default ui-accordion-sub-icons ui-accordion-header-active ui-state-active ui-corner-top ui-accordion-sub"); 
                    } else {
                        $prev.addClass("accordion-header ui-accordion-header ui-helper-reset ui-state-default ui-accordion-icons ui-accordion-header-active ui-state-active ui-corner-top");   
                    }
                }
        
        
            },
            // whenever we close a panel, check to see if they're all open
            // if not all open, swap the button to expander
            hide: function() {
                var isAllOpen = !contentAreas.is(':hidden');
                if (!isAllOpen) {
                    
                    expandLink.data('isAllOpen', false)
                            .children("i").removeClass().addClass("glyphicon glyphicon-plus");
                }
                
                if($(this).is(selected)){
                    var $prev = $(this).prev();
                    var $isSub = $prev.hasClass( "ui-accordion-sub" );
    //                $prev.removeClass();
    //                $prev.addClass("accordion-header ui-accordion-header ui-helper-reset ui-state-default ui-accordion-icons ui-corner-all"); 
    //                
    //                
    //                $prev.addClass("accordion-header ui-accordion-header ui-helper-reset ui-sub-state-default ui-accordion-sub-icons ui-corner-all ui-accordion-sub"); 
                    $prev.removeClass();
                    if($isSub){
                        $prev.addClass("accordion-header ui-accordion-header ui-helper-reset ui-sub-state-default ui-accordion-sub-icons ui-corner-all ui-accordion-sub"); 
                    } else {
                        $prev.addClass("accordion-header ui-accordion-header ui-helper-reset ui-state-default ui-accordion-icons ui-corner-all"); 
                    }
                }
            }
        }).slideUp().hide();
    });
});