$(function () {

    function displayResult(item) {
        $('.alert').show().html('You selected <strong>' + item.value + '</strong>: <strong>' + item.text + '</strong>');
    }

    $.get($getAllSymbol, function (data) {
        $("#symbol").typeahead({source: data, onSelect: displayResult});
    }, 'json');


    $(".datepicker").datepicker({dateFormat: 'yy-mm-dd'});

    var $fromDate = new Date();
    $fromDate.setMonth($fromDate.getMonth() - 60);
    $("#fromDate").datepicker("setDate", $fromDate);

    $("#toDate").datepicker("setDate", new Date());


    $("#searchButton").click(function () {
        var $val = $("#symbol").val();
        var $fromDate = $("#fromDate").val();
        var $toDate = $("#toDate").val();
        
        $fromDate = new Date($fromDate).getTime() / 1000;
        $toDate = new Date($toDate).getTime() / 1000;

        var $url = $getSingleStock + "?symbol=" +  $val
                + "&fromDate=" + $fromDate 
                + "&toDate=" + $toDate;
        if (!$val) {
            return;
        }
        $(function () {
            $.getJSON($url, function (data) {

//            $.getJSON("http://www.highcharts.com/samples/data/jsonp.php?a=e&filename=aapl-ohlcv.json&callback=?", function (data) {
                // create the chart
                initHighCharts("#container", data);
            });
            
        });

    }).click();


    
});




//$(function () {
//    var seriesOptions = [],
//        seriesCounter = 0,
//        names = ['MSFT', 'AAPL', 'GOOG'],
//        // create the chart when all data is loaded
//        createChart = function () {
//
//            $('#container').highcharts('StockChart', {
//
//                rangeSelector: {
//                    selected: 4
//                },
//
//                yAxis: {
//                    labels: {
//                        formatter: function () {
//                            return (this.value > 0 ? ' + ' : '') + this.value + '%';
//                        }
//                    },
//                    plotLines: [{
//                        value: 0,
//                        width: 2,
//                        color: 'silver'
//                    }]
//                },
//
//                plotOptions: {
//                    series: {
//                        compare: 'percent'
//                    }
//                },
//
//                tooltip: {
//                    pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y}</b> ({point.change}%)<br/>',
//                    valueDecimals: 2
//                },
//
//                series: seriesOptions
//            });
//        };
//
//    $.each(names, function (i, name) {
//
//        $.getJSON('http://www.highcharts.com/samples/data/jsonp.php?filename=' + name.toLowerCase() + '-c.json&callback=?',    function (data) {
//
//            seriesOptions[i] = {
//                name: name,
//                data: data
//            };
//
//            // As we're loading the data asynchronously, we don't know what order it will arrive. So
//            // we keep a counter and create the chart when all the data is loaded.
//            seriesCounter += 1;
//
//            if (seriesCounter === names.length) {
//                createChart();
//            }
//        });
//    });
//});
