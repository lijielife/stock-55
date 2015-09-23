var $table;

$(function () {

    $.get($getAllSymbol, function (data) {
        $("#symbol").typeahead({source: data});
    }, 'json');

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
    
    var tigger = true, tempRows = null;
    $("#searchButton").click(function(){
        $table.bootstrapTable('refresh');
        tigger = true;
        tempRows = null;
    });
         
    $("#clearButton").click(function(){
        $("#symbol").val('');
        $('#brokerMenu').val( $('#brokerMenu').prop('defaultSelected') );
    });
    
    $("#matcherButton").click(function(){
        var rows = $table.bootstrapTable("getData")
        , newRows = [];
        if(tempRows === null){
            tempRows = rows; 
        }
        if(tigger){
            $.each(tempRows, function(index, row){
                if(row.MATCHER == null){
                    newRows.push(row);
                }
            });
        } else {
            newRows = tempRows;
        }
        tigger = !tigger;
        
        $table.bootstrapTable("load", newRows);
        
        $table.bootstrapTable("uncheckAll");
        resultText();
    });
    
    
    $table = $('#tbl').bootstrapTable()
      .on('check.bs.table', function (e, row) {
//        resultText('Event: check.bs.table, data: ' + JSON.stringify(row));
        calSelection();
    }).on('uncheck.bs.table', function (e, row) {
//        resultText('Event: uncheck.bs.table, data: ' + JSON.stringify(row));
        calSelection();
    }).on('check-all.bs.table', function (e) {
//        resultText('Event: check-all.bs.table');
        calSelection();
    }).on('uncheck-all.bs.table', function (e) {
//        resultText('Event: uncheck-all.bs.table');
        calSelection();
    });
});

function calSelection(){
    var $rows = $table.bootstrapTable('getSelections');
    var $sumAmount = 0, $sumVol = 0,$avg = 0;
    $.each($rows, function($index, $row){
//        var $price = $row.PRICE;
        var $side = $row.SIDE_CODE;
        var $vol = $row.VOLUME * ($side === '002' ? -1 : 1);
        var $amount = $row.NET_AMOUNT * ($side === '002' ? -1 : 1);
        
        $sumAmount += $amount;
        $sumVol += $vol;
    });
    $avg = $sumAmount / $sumVol;
    
    $('#events-result').text($sumAmount + " / " + $sumVol + " = " + ($avg).formatMoney(4));
    
}

function resultText(string){
    $('#events-result').text((string ? string : 'Result'));
}
    
    

function cellStyle(value, row, index) {
    var $classSide = (
                row.SIDE_CODE == '001' ? 'info' : 
                (
                    row.SIDE_CODE == '002'? 'danger' : 'success'
                )
        );
    return {
        classes: $classSide
    };

}


function cellStyleMatcher(value, row, index) {
    var $classSide = (row.MATCHER !== null ? 'active' : '');
            
    return {
        classes: $classSide
    };

}

function cellValueStyle(value, row, index) {
    var $classText = (value > 0 ? 'text-success' :
            (value = 0 ? 'text-warning' :'text-danger'));
    return {
        classes: $classText
    };
}

function cellValuePercentStyle(value, row, index) {
    var $classText = (value > 100 ? 'text-success' :
            (value == 100 ? 'text-warning' :'text-danger'));
    return {
        classes: $classText
    };
}

function cellValueAvgStyle(value, row, index, rows) {
    var datas = $table.bootstrapTable("getData");
    var beforeValue = (datas[index+1] ? datas[index+1] : row).AVG_PRICE;
    var $classText = (value > beforeValue ? 'text-danger' :
            (value == beforeValue ? 'text-warning' :'text-success'));
    return {
        classes: $classText
    };
}

function rowStyle(row, index) {
//    var classes = ['active', 'success', 'info', 'warning', 'danger'];
 var $classMatcher = (
            row.MATCHER !== null ? (
                row.SIDE_CODE == '001' ? 'active-info' : 
                (
                    row.SIDE_CODE == '002'? 'active-danger' : 'active-success'
                )
            ) :
            (
                row.SIDE_CODE == '001' ? 'info' : 
                (
                    row.SIDE_CODE == '002'? 'danger' : 'success'
                )
            )
        );

//$classSide = (
//                row.SIDE_CODE == '001' ? 'info' : 
//                (
//                    row.SIDE_CODE == '002'? 'danger' : 'success'
//                )
//        );
//    var date = new Date(row.DATE).getTime()
//            , dueDate = new Date(row.DUE_DATE).getTime()
//            , diffDate = parseInt((date - dueDate) / (24 * 3600 * 1000));
                                
    return {
        classes: $classMatcher
    };
}


function rowAttributes(row, index) {
//    var classes = ['active', 'success', 'info', 'warning', 'danger'];
    var $classMatcher = (
            row.MATCHER !== null ? 'gray' :
            (
                row.SIDE_CODE == '001' ? 'info' : 
                (
                    row.SIDE_CODE == '002'? 'danger' : 'success'
                )
            )
        );

//$classSide = (
//                row.SIDE_CODE == '001' ? 'info' : 
//                (
//                    row.SIDE_CODE == '002'? 'danger' : 'success'
//                )
//        );
//    var date = new Date(row.DATE).getTime()
//            , dueDate = new Date(row.DUE_DATE).getTime()
//            , diffDate = parseInt((date - dueDate) / (24 * 3600 * 1000));
                                
    return {
        classes: $classMatcher
    };
}


function queryParams(row, index) {
//    var classes = ['active', 'success', 'info', 'warning', 'danger'];
    var symbol = $("#symbol").val()
    , broker = $("#brokerMenu option:selected").val();
    
    return {
        symbol : symbol
        ,broker : broker
    };
}

function numFormatter(value, row) {
    return (value).formatMoney(0);
}

function numFormatter2(value, row) {
    return (value).formatMoney(2);
}

function numFormatter4(value, row) {
    return (value).formatMoney(4);
}

function priceFormatter(value) {
    // 16777215 == ffffff in decimal
    var color = '#'+Math.floor(Math.random() * 6777215).toString(16);
    return '<div  style="color: ' + color + '">' +
            '<i class="glyphicon glyphicon-usd"></i>' +
            value.substring(1) +
            '</div>';
}

Number.prototype.formatMoney = function(c, d, t){
var n = this, 
    c = isNaN(c = Math.abs(c)) ? 2 : c, 
    d = d == undefined ? "." : d, 
    t = t == undefined ? "," : t, 
    s = n < 0 ? "-" : "", 
    i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "", 
    j = (j = i.length) > 3 ? j % 3 : 0;
   return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
 };
 

