var $table, $footerTr;

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
    $("#searchButton").click(function () {
        $table.bootstrapTable('refresh');
        tigger = true;
        tempRows = null;
    });

    $("#clearButton").click(function () {
        $("#symbol").val('');
        $('#brokerMenu').val($('#brokerMenu').prop('defaultSelected'));
    });

    $("#matcherButton").click(function () {
        var rows = $table.bootstrapTable("getData")
                , newRows = [];
        if (tempRows === null) {
            tempRows = rows;
        }
        if (tigger) {
            $.each(tempRows, function (index, row) {
                if (row.MATCHER == null) {
                    newRows.push(row);
                }
            });
        } else {
            newRows = tempRows;
        }
        tigger = !tigger;

        $table.bootstrapTable("load", newRows);

    });

    $("#loadPrice").click(function(){
        var $selections = $table.bootstrapTable('getSelections'),
            $symbols = [];
        $.each($selections, function(index, $obj){
            var $symbol = $obj.SYMBOL;
            $symbols.push($symbol);
        });
        $symbols = $symbols.join();
        $("#loadPrice").find("i").css({color: "orange"});
        $.get($loadUrl, {symbols: $symbols} )
        .done(function () {
            $("#loadPrice").find("i").css({color: "greenyellow"});
        })
        .fail(function(){
            $("#loadPrice").find("i").css({color: "red"});
        })
        .always(function(){
            $table.bootstrapTable('refresh');
        }); 
    });
    
    $("#showActive").click(function(){
        var $showActive = $("#showActive").hasClass("btn-primary");
        
        $.get($showActiveUrl, {showActive: !$showActive} )
        .done(function () {
            if($showActive){
                $("#showActive").removeClass().addClass("btn btn-default");
            } else {
                $("#showActive").removeClass().addClass("btn btn-primary");
            }
            $table.bootstrapTable('refresh');
        })
        .fail(function(){
            $("#showActive").removeClass().addClass("btn btn-danger");
        });
    });
    
    
    $table = $('#tbl').bootstrapTable()
    
    .on('load-success.bs.table', function (e, data) {
        
        
       $($footerTr).remove();

        $footerTr = $(".fixed-table-footer").find(".table tr").hide().clone(true).show();

        $.each($footerTr.find("td"), function(){
            $(this).replaceWith('<th style="'+$(this).attr("style")+'">'+$(this).html()+'</th>');
        });
        $(this).find("thead").append($footerTr);
    });

    $("#deleteTestAllButton").click(function(){
        
        $("#deleteTestAllButton").find("i").css({color: "orange"});
        $.get($deleteTestAllUrl, {})
        .done(function(){
            $("#deleteTestAllButton").find("i").css({color: "greenyellow"});
        })
        .fail(function(){
            $("#deleteTestAllButton").find("i").css({color: "red"});
        })
        .always(function(){
            $table.bootstrapTable('refresh');
        }); 
            
            
    });
});

function symbolFormatter(value, row) {
    var $symbol = row.SYMBOL,
    $brokerId = $("#brokerMenu :selected").val();
    return '<a href="'+$profileUrl+'?symbol='+$symbol+'&broker='+$brokerId+'" target="_blank">' + value + '</a> ' ;
}
    
function cellStyle(value, row, index) {
    var $classSide = (
            row.SIDE_CODE == '001' ? 'info' :
            (
                    row.SIDE_CODE == '002' ? 'danger' : 'success'
                    )
            );
    return {
        classes: $classSide
    };

}

function cellValueStyle(value, row, index) {
    var $classText = (row.RESULT > 0 ? 'text-success' :
            (row.RESULT == 0 ? 'text-warning' : 'text-danger'));
    return {
        classes: $classText
    };
}

function cellPortIndexStyle(value, row, index) {
    var $classText = (row.PORT_INDEX > 100 ? 'text-success' :
            (row.PORT_INDEX == 100 ? 'text-warning' : 'text-danger'));
    return {
        classes: $classText
    };
}

function cellValueAvgStyle(avgPrice, row, index, rows) {
//    var datas = $table.bootstrapTable("getData");
//    var beforeValue = (datas[index + 1] ? datas[index + 1] : row).AVG_PRICE;

    var $classText = (avgPrice > row.PRICE_IN_DAY ? 'text-danger' :
            (avgPrice === row.PRICE_IN_DAY ? 'text-warning' : 'text-success'));
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
                            row.SIDE_CODE == '002' ? 'active-danger' : 'active-success'
                            )
                    ) :
            (
                    row.SIDE_CODE == '001' ? 'info' :
                    (
                            row.SIDE_CODE == '002' ? 'danger' : 'success'
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
                            row.SIDE_CODE == '002' ? 'danger' : 'success'
                            )
                    )
            );

    return {
        classes: $classMatcher
    };
}


function queryParams(row, index) {
//    var classes = ['active', 'success', 'info', 'warning', 'danger'];
    var symbol = $("#symbol").val()
            , broker = $("#brokerMenu option:selected").val();

    return {
        symbol: symbol
        , broker: broker
    };
}

function priceFormatter(value) {
    // 16777215 == ffffff in decimal
    var color = '#' + Math.floor(Math.random() * 6777215).toString(16);
    return '<div  style="color: ' + color + '">' +
            '<i class="glyphicon glyphicon-usd"></i>' +
            value.substring(1) +
            '</div>';
}

Number.prototype.formatMoney = function (c, d, t) {
    var n = this,
            c = isNaN(c = Math.abs(c)) ? 2 : c,
            d = d == undefined ? "." : d,
            t = t == undefined ? "," : t,
            s = n < 0 ? "-" : "",
            i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "",
            j = (j = i.length) > 3 ? j % 3 : 0;
    return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
};


function runningFormatter(value, row, index) {
    return index;
}

function totalTextFormatter(data) {
    return 'Total';
}

function totalFormatter(data) {
    return data.length;
}

function sumFormatter(data) {
    field = this.field;
    return data.reduce(function (sum, row) {
        return sum + (+row[field]);
    }, 0).formatMoney(2);;
}

function avgFormatter(data) {
    return sumFormatter.call(this, data) / data.length;
}

function percentFormatter(data) {
    var $fieldResult = "RESULT",
    $fieldValue = "VALUE",
    $result = data.reduce(function (sum, row) {
            return sum + (+row[$fieldResult]);
        }, 0),
    $value  = data.reduce(function (sum, row) {
            return sum + (+row[$fieldValue]);
        }, 0);
        
    return (($result / $value) * 100).formatMoney(2);
    
    
}

//function operateFormatter(value, row, index) {
//    return [
//        '<a class="like" href="javascript:void(0)" title="Like">',
//            '<i class="glyphicon glyphicon-heart"></i>',
//        '</a>',
//        '<a class="edit ml10" href="javascript:void(0)" title="Edit">',
//            '<i class="glyphicon glyphicon-edit"></i>',
//        '</a>',
//        '<a class="remove ml10" href="javascript:void(0)" title="Remove">',
//            '<i class="glyphicon glyphicon-remove"></i>',
//        '</a>'
//    ].join('');
//}
    