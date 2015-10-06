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
    
    $("#addBuyButton").click(function(){
        var $tr = getTrTmpl();
        if($tr.hasClass("no-records-found")){
            return ;
        }
        
        $tr.find("td:eq(2)").removeClass("danger").addClass("info");
        
        $("tbody:first").prepend($tr);
        
    });
    $("#addSellButton").click(function(){
        var $tr = getTrTmpl();
        if($tr.hasClass("no-records-found")){
            return ;
        }
        
        $tr.find("td:eq(2)").removeClass("info").addClass("danger");
        
        $("tbody:first").prepend($tr);
        
        
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

function getTrTmpl(){
//     <tr data-index="-1">
//    <td class="bs-checkbox">
//    <input data-index="0" name="btSelectItem" type="checkbox">
//    </td>
//    
//    <td style="text-align: center; ">2015-10-06</td> 
//    <td class="info" style="text-align: right; width: 100px; ">
//    <input type="text" class="form-control volume" style="height: 20px;">
//    </td>
//    <td style="text-align: right; width: 100px; ">
//        <input type="text" class="form-control price" style="height: 20px;">
//    </td>
//    <td style="text-align: right; ">-</td>
//    <td style="text-align: right; ">4</td>
//    <td style="text-align: right; ">678.85</td>
//    <td class="text-danger" style="text-align: right; ">-250.58</td>
//    <td class="text-danger" style="text-align: right; ">-1.09</td>
//    <td class="text-success" style="text-align: right; ">130.55</td>
//    <td class="text-warning" style="text-align: right; ">232.3575</td>
//    </tr>
    
    var $tr = $("<tr data-index='-1'></tr>");
    
    
    var $tr = $("tbody:first").find("tr:first").clone(true).attr("data-index", "-1");
    
    if($tr.hasClass("no-records-found")){
        return null;
    }

        $inputTmpl = $('<input type="text" class="form-control"/>').height("20px");
        
        $tr.find("td:eq(2)").html($inputTmpl.clone(true).addClass("volume"));
        $tr.find("td:eq(3)").html($inputTmpl.clone(true).addClass("price"));
    return $tr;  
}
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
    var $classSide = (row.MATCHER !== null && typeof(row.MATCHER) !== "undefined" ? 'active' : '');
            
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
 

