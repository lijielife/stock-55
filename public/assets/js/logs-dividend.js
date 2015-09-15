var $table;

$(function () {

    $.get($getAllSymbol, function (data) {
        $("#symbol").typeahead({source: data});
    }, 'json');

    $("#searchButton").click(function(){
        $table.bootstrapTable('refresh');
    });
         
    $("#clearButton").click(function(){
        $("#symbol").val('');
        $('#brokerMenu').val( $('#brokerMenu').prop('defaultSelected') );
    });
                    
//    $('#hover, #striped, #condensed').click(function () {
//        var classes = 'table';
//
//        if ($('#hover').prop('checked')) {
//            classes += ' table-hover';
//        }
//        if ($('#condensed').prop('checked')) {
//            classes += ' table-condensed';
//        }
//        $('#table-style').bootstrapTable('destroy')
//                .bootstrapTable({
//                    classes: classes,
//                    striped: $('#striped').prop('checked')
//                });
//    });

//    $('#tbl').bootstrapTable();
//    $('#filter-bar').bootstrapTableFilter({
//        connectTo: '#tbl',
////        onAll: function (name, args) {
////            var d = new Date();
////            $('#log').prepend(d.toLocaleString() + ': ' + name + "\n");
////        },
////        onSubmit: function (data) {
////            var data = $('#filter-bar').bootstrapTableFilter('getData');
////            var d = new Date();
////            $('#log').prepend(d.toLocaleString() + ': ' + JSON.stringify(data) + "\n");
////        },
//        filters:[
//            {field: 'SYMBOL', label: 'SYMBOL', 
//                type: 'select',
//                values: [
//                    {id: 'BLAND', label: 'BLAND'}
//                ],
//            }
//            ,{field: 'DATE', label: 'Date', type: 'range'}
//            ,{field: 'DUE_DATE', label: 'Due Date', type: 'range'}
//        ]
//    });

    $table = $('#tbl').bootstrapTable();
        
});

function rowStyle(row, index) {
//    var classes = ['active', 'success', 'info', 'warning', 'danger'];

    var date = new Date(row.DATE).getTime()
            , dueDate = new Date(row.DUE_DATE).getTime()
            , diffDate = parseInt((date - dueDate) / (24 * 3600 * 1000));

    return {
        classes: (diffDate !== 0 ? 'success' : 'active')
    };
}


function rowAttributes(row, index) {
//    var classes = ['active', 'success', 'info', 'warning', 'danger'];

    var date = new Date(row.DATE).getTime()
            , dueDate = new Date(row.DUE_DATE).getTime()
            , diffDate = parseInt((date - dueDate) / (24 * 3600 * 1000));

    return {
        classes: (diffDate !== 0 ? 'success' : 'active')
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
