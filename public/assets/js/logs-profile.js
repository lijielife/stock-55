var $table, $symbolLocal;
$(function () {
    
    $.data(document.body, "tt", []);
    $.data(document.body, "LID", 0);
    
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
        
        $.data(document.body, "tt", []);
        $table.bootstrapTable('refresh');
        tigger = true;
        tempRows = null;
    });
    
    $('#symbol').keypress(function (e) {
        if (e.which === 13) {
            $("#searchButton").click();
            return false;    //<---- Add this line
        }
    });

    $("#cleanButton").click(function(){
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
                if(row.MATCHER === null){
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
        loadDataTT("001");
    });
    $("#addSellButton").click(function(){
        loadDataTT("002");
    });
    $("#addDivButton").click(function(){
        loadDataTT("003");
    });
    $('#removeButton').click(function () {
        var selects = $table.bootstrapTable('getSelections'),
        idToDel = $.map(selects, function (row) {
            return ($.isNumeric(row.LID) ? row.LID : null);
        }),
        reqArr = $.data(document.body, "tt");
        $.data(document.body, "tt", reqArr.filter(function(obj) {
                return $.inArray( obj.LID+"" , idToDel ) === -1;
            })
        );
        $table.bootstrapTable('refresh');
    });
    $("#saveButton").click(function(){
        var selects = $table.bootstrapTable('getSelections'),
        $rowToSave = $.map(selects, function (row) {
            return ($.isNumeric(row.LID) ? row : null);
        });
        $.get($saveDataUrl, {tt : $rowToSave})
        .done(function(data){
            $("#symbol").typeahead({source: data});
        });
    });
    $("#deleteTestButton").click(function(){
        $.get($deleteTestUrl, {symbol : $symbolLocal})
        .done(function(){
            $table.bootstrapTable('refresh');  
        });
    });
    
    $("#loadPriceButton").click(function(){
//        var $selections = $table.bootstrapTable('getSelections'),
//            $symbols = [];
//        $.each($selections, function(index, $obj){
//            var $symbol = $obj.SYMBOL;
//            $symbols.push($symbol);
//        });
//        $symbols = $symbols.join();
        
//        $.get($loadUrl+"?symbols="+$symbolLocal, function (data) {
//            $table.bootstrapTable('refresh'); 
////            window.location.reload(true); 
//        }, 'json');
        
        $("#loadPriceButton").find("i").css({color: "orange"});
        $.get($loadUrl, {symbols: $symbolLocal} )
        .done(function () {
//            window.location.reload(true); 
            $table.bootstrapTable('refresh'); 
            $("#loadPriceButton").find("i").css({color: "greenyellow"});
        }).fail(function(){
            $table.bootstrapTable('refresh'); 
            $("#loadPriceButton").find("i").css({color: "red"});
        });
        
        
    });
    
    initTableEvent();
});

function loadDataTT($sideCode){
    var $row = getRowObj($sideCode);
    if($row !== null){
        var reqArr = $.data(document.body, "tt");
        reqArr.push($row);
        $table.bootstrapTable('refresh');
    }
}

function getRowObj($sideCode){
    
    var $volume = $("#volume").val(),
        $price = $("#price").val();
    if($.isNumeric($volume) && $.isNumeric($price)){
        var $lid = $.data(document.body, "LID");
        return {
            SYMBOL : null,
            SIDE_CODE : $sideCode,
            VOLUME : $volume,
            PRICE : $price,
            NET_AMOUNT : null,
            DATE : null,
            LID : $.data(document.body, "LID", ++$lid)
        };
    }
    return null;
}

function initTableEvent(){
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
    }).on('load-success.bs.table', function(e, row) {
        var datas = $table.bootstrapTable('getData');
        $.each(datas, function(index, value){
            
        });
    });
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
    var $classText = (row.RESULT > 0 ? 'text-success' :
            (row.RESULT = 0 ? 'text-warning' :'text-danger'));
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
    , broker = $("#brokerMenu option:selected").val()
    , tt = $.data(document.body, "tt");
    $symbolLocal = symbol;
    return {
        symbol : symbol
        ,broker : broker
        ,tt : tt
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


