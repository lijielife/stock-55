var $chart;
        //= $('#container').highcharts();
     
getGroupingByOption = function(option){
    return [[
        'day', option[0]
    ] , [
        'week',option[1]
    ] , [
        'month',option[2]
    ]];
};

changeGrouping = function(option){
    $chart = window.$chart;
    for (var i = 0; i < $chart.series.length; i++){
        $chart.series[i].update({
            dataGrouping: {
//                approximation: "sum",
                forced: true,
//                enabled: enable,
                units: getGroupingByOption(option)
            }
        });
    }
};

$(function(){
   $("#timeFrame").change(function(){
        var timeFrameCode = $(this).find("option:selected").val(); 
        if(timeFrameCode === "1"){
            changeGrouping([[1], [], []]);
        } else if(timeFrameCode === "2"){
            changeGrouping([[], [1], []]);
        } else if(timeFrameCode === "3"){
            changeGrouping([[], [], [1]]);
        }
   });
});

initHighCharts = function($id, $data) {
    var ohlc = [],
            volume = [],
            close = [],
            dataLength = $data.length,
            enabledDataGrouping = true,
            // set the allowed units for data grouping
         dataGrouping = {
            dataGrouping: {
                forced: true,
                enabled: enabledDataGrouping,
                units: getGroupingByOption([[1],[],[1]])
            }
        },i = 0;

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

//    var $ema9 = ema(close, 9);
    var $ema12 = ema(close, 12);
    var $ema26 = ema(close, 26);

    var macdLindObj = macdLine(close, 12, 26, 9);
    var seriesOption = [{
            type: 'candlestick',
            name: 'Stock',
            data: ohlc,
            id: 'primary',
            yAxis: 0,
            dataGrouping: dataGrouping
        }
//        , {
//            type: 'line',
//            name: 'EMA 12',
//            data: $ema12,
//            id: 'primary',
//            yAxis: 0,
//            dataGrouping: dataGrouping
//        }
//        , {
//            type: 'line',
//            name: 'EMA 26 ',
//            data: $ema26,
//            yAxis: 0,
//            dataGrouping: dataGrouping
//        }
//        
        , {
                name: '15-day SMA',
                linkedTo: 'primary',
                showInLegend: true,
                type: 'trendline',
                algorithm: 'SMA',
                periods: 12
            }, {
                name: '40-day SMA',
                linkedTo: 'primary',
                showInLegend: true,
                type: 'trendline',
                algorithm: 'SMA',
                periods: 26
            }
            
        , {
                name : 'MACD',
                linkedTo: 'primary',
                yAxis: 1,
                showInLegend: true,
                type: 'trendline',
                algorithm: 'MACD',
                color: '#05FFFF',
                dataGrouping: dataGrouping

            }, {
                name : 'Signal line',
                linkedTo: 'primary',
                yAxis: 1,
                showInLegend: true,
                type: 'trendline',
                algorithm: 'signalLine',
                color: '#F9FB07',
                dataGrouping: dataGrouping

            }, {
                name: 'Histogram',
                linkedTo: 'primary',
                yAxis: 1,
                showInLegend: true,
                type: 'histogram',
                dataGrouping: dataGrouping,
                zones: [{
                   value: 0,
                   color: 'red'
                }, {
                   color: 'blue'
                }]
            }
            
//            , {
//            type: 'line',
//            name: 'Line',
//            data: macdLindObj.macdLine,
//            yAxis: 1,
//            color: '#05FFFF',
//            dataGrouping: dataGrouping
//        }, {
//            type: 'line',
//            name: 'Signal',
//            data: macdLindObj.macdSignal,
//            yAxis: 1,
//            color: '#F9FB07',
//            dataGrouping: dataGrouping
//        } , {
//            type: 'column',
//            name: 'Histogrm',
//            data: macdLindObj.macdHistogrm,
//            yAxis: 1,
//            dataGrouping: dataGrouping,
//            zones: [{
//               value: 0,
//               color: 'red'
//            }, {
//               color: 'blue'
//            }]
//        } 
        , {
            type: 'column',
            name: 'Volume',
            data: volume,
            yAxis: 2,
            dataGrouping: dataGrouping
        }];

    $chart = $($id).highcharts('StockChart', {
        exporting: {enabled: false},
        rangeSelector: {
            selected: 1
        },
        plotOptions: {
            candlestick: {
                color: '#FE0001',
                upColor: '#1a8ff8',
                lineColor: '#FE0001',
                upLineColor: '#1a8ff8'
            },
            series: {
                animation: {
                    duration: 500,
                    easing: 'swing'
                }
            },
            column: {
                states: {
                    hover: {
                        enabled: false,
                        color: '#000000'                                                           
                    }
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
                height: '70%',
                lineWidth: 1
            }, {
                labels: {
                    align: 'right',
                    x: -3
                },
                title: {
                    text: 'MACD'
                },
                top: '70%',
                height: '20%',
                offset: 0,
                lineWidth: 1
            }, {
                labels: {
                    align: 'right',
                    x: -3
                },
                title: {
                    text: 'Volume'
                },
                top: '90%',
                height: '10%',
                offset: 0,
                lineWidth: 2
            }],
        series: seriesOption
    }).highcharts();
    $($id).focus();
};


ema = function($close, $day) {
    var $emaRet = [];
    var $closeArray = [];
    $.each($close, function(index, value) {
        var $millisec = value[0];
        var $close = value[1];
        $closeArray.push($close);
        if ($closeArray.length >= $day) {
            var $emaDay = $closeArray.slice($closeArray.length - $day, $closeArray.length);
            var $sum = 0;
            $.each($emaDay, function($index, $value) {
                $sum += $value;
            });
            var $ema = $sum / $day;

            $emaRet.push([$millisec, $ema]);
        }
    });
    return $emaRet;
};


macdLine = function($close, $day1, $day2, $day3) {

//    MACD Line = EMA(12 ($day1)) – EMA(26 ($day2))
//    Signal Line = EMA(9 ($day3))

    var $macdLineRet = [];
    var $macdLineTemp = [];
    var $closeArray = [];
    $.each($close, function(index, value) {
        var $millisec = value[0];
        var $close = value[1];
        $closeArray.push($close);

        var $emaFromDay1 = sumEma($closeArray, $day1)
                , $emaFromDay2 = sumEma($closeArray, $day2)
                , $emaFromDay3 = sumEma($closeArray, $day3)
                , macdLine = null, $macdSignalLine = null;

        if ($emaFromDay1 !== null && $emaFromDay2 !== null) {
            macdLine = $emaFromDay1 - $emaFromDay2;
            $macdLineRet.push([$millisec, macdLine]);
            $macdLineTemp[$millisec] = macdLine;
        }

//        if ($emaFromDay3 !== null) {
//            $macdSignalLine = $emaFromDay3;
//            $macdSignalRet.push([$millisec, $macdSignalLine]);
//        }
    });
    
    var $macdSignalRet = ema($macdLineRet, 9);
    var $macdHistogrm = [];
    
    $.each($macdSignalRet, function(index, value) {
        var $millisec = value[0];
        var $macdSignal = value[1];
        var $macdLine = $macdLineTemp[$millisec];
        
        
        $macdHistogrm.push([$millisec, $macdLine - $macdSignal]);
        
    });
    
    return {
        macdLine: $macdLineRet
        , macdSignal: $macdSignalRet
        , macdHistogrm: $macdHistogrm
    };
};



sumEma = function($closeArray, $day) {

    if ($closeArray.length >= $day) {
        var $emaDay = $closeArray.slice($closeArray.length - $day, $closeArray.length);
        var $sum = 0;
        $.each($emaDay, function($index, $value) {
            $sum += $value;
        });
        return $sum / $day;
    } else {
        return null;
    }

//    $macdLineRet.push([$millisec, $ema]);

};