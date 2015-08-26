initHighCharts = function ($id, $data) {
    var ohlc = [],
            volume = [],
            close = [],
            dataLength = $data.length,
            // set the allowed units for data grouping
            groupingUnits = [
                [
                    'week', // unit name
                    [1]                             // allowed multiples
                ], [
                    'month',
                    [1, 2, 3, 4, 6]
                ]],
            i = 0;

    for (i; i < dataLength; i += 1) {
        ohlc.push([
            $data[i][0], // the date
            $data[i][1], // open
            $data[i][2], // high
            $data[i][3], // low
            $data[i][4] // close
        ]);

        volume.push([
            $data[i][0], // the date
            $data[i][5] // the volume
        ]);

        close.push([
            $data[i][0], // the date
            $data[i][4] // close
        ]);
    }


    // create the chart
    $($id).highcharts('StockChart', {
        exporting: {enabled: false},
        rangeSelector: {
            selected: 1
        },
        plotOptions: {
            candlestick: {
                color: '#1a8ff8',
                upColor: 'red'
            },
            series: {
                animation: {
                    duration: 2000,
                    easing: 'swing'
                }
            }
        },
//        title: {
//            text: 'Single Stock'
//        },
        yAxis: [{
                labels: {
                    align: 'right',
                    x: -3
                },
                title: {
                    text: 'Price'
                },
                height: '85%',
                lineWidth: 2
            }, {
                labels: {
                    align: 'right',
                    x: -3
                },
                title: {
                    text: 'Volume'
                },
                top: '85%',
                height: '15%',
                offset: 0,
                lineWidth: 2
            }],
        series: [{
                type: 'candlestick',
                name: 'Stock',
                data: ohlc,
                yAxis: 0,
                dataGrouping: {
                    enabled: false,
                    units: groupingUnits
                }
            }, {
                type: 'line',
                name: 'Close',
                data: close,
                yAxis: 0,
                dataGrouping: {
                    enabled: false,
                    units: groupingUnits
                }
            }, {
                type: 'column',
                name: 'Volume',
                data: volume,
                yAxis: 1,
                dataGrouping: {
                    enabled: false,
                    units: groupingUnits
                }
            }]
    });


//    $($id).highcharts('StockChart', {
//        exporting: {enabled: false},
//        rangeSelector: {
//            selected: 1
//        },
//        plotOptions: {
//            candlestick: {
//                color: '#1a8ff8',
//                upColor: 'red'
//            }
//        },
//        title: {
//            text: 'Single Stock'
//        },
//        series: [{
//                type: 'candlestick'
//                , name: 'Single Stock'
//                , data: $data
//                , dataGrouping: {
//                    enabled: false,
//                    units: [
//                        [
//                            'week', // unit name
//                            [1] // allowed multiples
//                        ], [
//                            'month',
//                            [1, 2, 3, 4, 6]
//                        ]
//                    ]
//                }
//            }]
//    });

    $($id).focus();
};
