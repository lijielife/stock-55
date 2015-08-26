/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */



$(function () {

    function displayResult(item) {
        $('.alert').show().html('You selected <strong>' + item.value + '</strong>: <strong>' + item.text + '</strong>');
    }

    $.get($getAllSymbol, function (data) {
        $("#symbol").typeahead({source: data, onSelect: displayResult});
    }, 'json');


    $(".datepicker").datepicker({dateFormat: 'dd-mm-yy'});

    var $fromDate = new Date();
    $fromDate.setMonth($fromDate.getMonth() - 6);
    $("#fromDate").datepicker("setDate", $fromDate);

    $("#toDate").datepicker("setDate", new Date());


    $("#searchButton").click(function () {
        var $val = $("#symbol").val();
        if (!$val) {
            return;
        }
        $(function () {
            $.getJSON($getSingleStock + $val, function (data) {

//            $.getJSON("http://www.highcharts.com/samples/data/jsonp.php?a=e&filename=aapl-ohlc.json&callback=?", function (data) {
                // create the chart
                initHighCharts("#container", data);
            });
        });

    }).click();


});