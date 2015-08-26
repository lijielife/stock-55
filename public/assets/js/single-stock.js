$(function () {

    function displayResult(item) {
        $('.alert').show().html('You selected <strong>' + item.value + '</strong>: <strong>' + item.text + '</strong>');
    }

    $.get($getAllSymbol, function (data) {
        $("#symbol").typeahead({source: data, onSelect: displayResult});
    }, 'json');


    $(".datepicker").datepicker({dateFormat: 'yy-mm-dd'});

    var $fromDate = new Date();
    $fromDate.setMonth($fromDate.getMonth() - 6);
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

//            $.getJSON("http://www.highcharts.com/samples/data/jsonp.php?a=e&filename=aapl-ohlc.json&callback=?", function (data) {
                // create the chart
                initHighCharts("#container", data);
            });
        });

    }).click();


});