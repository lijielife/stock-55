$(function() {

    $("#btnDelete").click(function() {
        $("#uploader").val('');
    });

//    $('body')

    $('#wrapper').height($(window).height()).enscroll({
        showOnHover: false,
        verticalTrackClass: 'track3',
        verticalHandleClass: 'handle3',
        verticalScrolling: !0, 
        horizontalScrolling: !1, 
        verticalScrollerSide: "right",
        scrollIncrement: 40,
        minScrollbarLength: 40,
        pollChanges: !0, 
        drawCorner: !0,
        drawScrollButtons: !1,
        clickTrackToScroll: !0,
        easingDuration: 500, 
        propagateWheelEvent: !0,
        horizontalTrackClass: "horizontal-track",
        horizontalHandleClass: "horizontal-handle",
        scrollUpButtonClass: "scroll-up-btn",
        scrollDownButtonClass: "scroll-down-btn",
        scrollLeftButtonClass: "scroll-left-btn",
        scrollRightButtonClass: "scroll-right-btn",
        cornerClass: "scrollbar-corner", zIndex: 1,
        addPaddingToPane: !0,
        horizontalHandleHTML: '<div class="left"></div><div class="right"></div>',
        verticalHandleHTML: '<div class="top"></div><div class="bottom"></div>'
    }).width("90%");;

    $('#scrollbox3').enscroll({
        showOnHover: false,
        verticalTrackClass: 'track3',
        verticalHandleClass: 'handle3',
        verticalScrolling: !0, 
        horizontalScrolling: !1, 
        verticalScrollerSide: "right",
        scrollIncrement: 40,
        minScrollbarLength: 40,
        pollChanges: !0, 
        drawCorner: !0,
        drawScrollButtons: !1,
        clickTrackToScroll: !0,
        easingDuration: 500, 
        propagateWheelEvent: !0,
        horizontalTrackClass: "horizontal-track",
        horizontalHandleClass: "horizontal-handle",
        scrollUpButtonClass: "scroll-up-btn",
        scrollDownButtonClass: "scroll-down-btn",
        scrollLeftButtonClass: "scroll-left-btn",
        scrollRightButtonClass: "scroll-right-btn",
        cornerClass: "scrollbar-corner", zIndex: 1,
        addPaddingToPane: !0,
        horizontalHandleHTML: '<div class="left"></div><div class="right"></div>',
        verticalHandleHTML: '<div class="top"></div><div class="bottom"></div>'
    }).width("90%");
    
});
