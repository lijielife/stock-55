$(function() {

//    $('body, #scrollbox3').enscroll({
//    showOnHover: false,
//            verticalTrackClass: 'track3',
//            verticalHandleClass: 'handle3',
//            verticalScrolling: !0, 
//            horizontalScrolling: !1, 
//            verticalScrollerSide: "right",
//            scrollIncrement: 40
//            , minScrollbarLength: 40,
//            pollChanges: !0, 
//            drawCorner: !0,
//            drawScrollButtons: !1,
//            clickTrackToScroll: !0,
//            easingDuration: 500, 
//            propagateWheelEvent: !0,
//            horizontalTrackClass: "horizontal-track",
//            horizontalHandleClass: "horizontal-handle",
//            scrollUpButtonClass: "scroll-up-btn",
//            scrollDownButtonClass: "scroll-down-btn",
//            scrollLeftButtonClass: "scroll-left-btn",
//            scrollRightButtonClass: "scroll-right-btn",
//            cornerClass: "scrollbar-corner", zIndex: 1,
//            addPaddingToPane: !0,
//            horizontalHandleHTML: '<div class="left"></div><div class="right"></div>',
//            verticalHandleHTML: '<div class="top"></div><div class="bottom"></div>'
//    }).width("90%");

    $(function() {
//        $("#accordion").accordion({
//            heightStyle: "content",
//            header: "h3",
//            collapsible: true
//        });

//        $('#accordion .ui-accordion-header').click(function() {
//            $(this).next().toggle();
//            return false;
//        }).next().hide();

//        $('#accordion .ui-accordion-header').click(function() {
//            $(this).next().toggle('slow');
//            return false;
//        }).next().hide();


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

//            $(this).removeClass();
//            if(isOpen){
//                $(this).prev().addClass("accordion-header ui-accordion-header ui-helper-reset ui-state-default ui-accordion-icons ui-corner-all");   
//            } else {
//                $(this).prev().addClass("accordion-header ui-accordion-header ui-helper-reset ui-state-default ui-accordion-icons ui-accordion-header-active ui-state-active ui-corner-top");   
//            }
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
                    expandLink.text('Collapse All')
                            .data('isAllOpen', true);
                }
                $(this).prev().removeClass();
                $(this).prev().addClass("accordion-header ui-accordion-header ui-helper-reset ui-state-default ui-accordion-icons ui-accordion-header-active ui-state-active ui-corner-top");   
            
//                $(this).next().removeClass();
//                $(this).next().addClass("accordion-header ui-accordion-header ui-helper-reset ui-state-default ui-accordion-icons ui-accordion-header-active ui-state-active ui-corner-top");   
            },
            // whenever we close a panel, check to see if they're all open
            // if not all open, swap the button to expander
            hide: function() {
                var isAllOpen = !contentAreas.is(':hidden');
                if (!isAllOpen) {
                    expandLink.text('Expand all')
                            .data('isAllOpen', false);
                }
                
                $(this).prev().removeClass();
                $(this).prev().addClass("accordion-header ui-accordion-header ui-helper-reset ui-state-default ui-accordion-icons ui-corner-all");   
            
            }
        }).slideUp().hide();
    });
});