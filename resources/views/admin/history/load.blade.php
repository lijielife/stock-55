@extends('admin.layouts.template')

@section('content')

<style>
    .ui-progressbar {
        position: relative;
    }
    .progress-label {
        position: absolute;
        left: 40%;
        top: 4px;
        font-weight: bold;
        text-shadow: 1px 1px 0 #fff;
    }
</style>

<div class="row show-grid">
    <div class="alert alert-block alert-success"></div>
</div>
<div class="row show-grid">
    <div class="col-sm-offset-2 col-md-offset-2 col-lg-offset-2 col-sm-8 col-md-8 col-lg-8">
        <div id="progressbar">
            <div class="progress-label">Loading...</div>
        </div>
    </div>
</div>

<script>


    var progressbar = $("#progressbar"),
            progressLabel = $(".progress-label"),
            tokenTimeOut, isSuccess = false, $status = {};


    var getText = function() {
        
            var text = $status.commit + " / " + $status.total + " = " + $status.percent + "%"

            if(isSuccess){
                text += " Success";
            }
            
            return text;
        
    }
    var getStatus = function() {
        $.ajax({
            url: "{{$urlGetStatus}}",
            async: true,
            dataType: "json"
        }).done(function(data) {
            $status = data;
            progressbar.progressbar("value", parseInt($status.percent, 10));
            
            progressLabel.text(getText());
            
            if ( !isSuccess ) {
                setTimeout( getStatus, 10000 );
            }

        });
    };
    $(function() {
//        $("#progressbar").progressbar({
//            value: 0
//        });
        progressbar.progressbar({
            value: false,
            change: function() {
                progressLabel.text(getText());
            },
            complete: function() {
                progressLabel.text("Complete!");
            }
        });
//        function progress() {
//            var val = progressbar.progressbar("value") || 0;
//            progressbar.progressbar("value", val + 2);
//            if (val < 99) {
//                setTimeout(progress, 80);
//            }
//        }

//        setTimeout(progress, 2000);


        getStatus();

        tokenTimeOut = setTimeout(getStatus, 10000);


        $.ajax({
            url: "{{$urlLoad}}",
            async: true,
            dataType: "json",
            success: function(data) {
                $.each(data, function(index, value) {
                    var row = $(".tr-row").clone(true);
                    row.removeClass().show();
                    row.find("#symbol").html(value.symbolName);
                    row.find("#count").html(value.count);
                    $(".tr-row").parent("tbody").append(row);
                });
//                clearTimeout(tokenTimeOut);
                isSuccess = true;
            }
        });

    });



</script>




<!-- /.row -->
<div class="panel panel-default">
    <div class="panel-body">
        <div class="dataTable_wrapper">
            <table class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Symbol</th>
                        <th>Count</th>
                    </tr>
                </thead>

                <tbody>
                    <tr class="tr-row" style="display: none;">
                        <td id="symbol"></td>
                        <td id="count"></td>
                    </tr>
                </tbody>

            </table>
        </div>
    </div>
</div>

@stop
