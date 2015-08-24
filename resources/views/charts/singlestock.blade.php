@extends('admin.layouts.template')
@section('content')


<script src="{{asset('/assets/bower_components/typeahead/js/bootstrap3-typeahead.js')}}"></script>
<!--<script src="{{asset('/assets/bower_components/typeahead/js/jquery.mockjax.js')}}"></script>-->
<!--<script src="{{asset('/assets/bower_components/typeahead/js/bootstrap-typeahead.js')}}"></script>-->
<!--<link href="{{asset('/assets/bower_components/typeahead/css/typeaheadjs.css')}}" rel="stylesheet">-->

        <!--<script src="https://google-code-prettify.googlecode.com/svn/loader/run_prettify.js"></script>-->

<div class="row">
    <div class="alert alert-block alert-success"></div>
</div>
<div class="row">
    <!--<div class="tab-pane" id="tab-demo4">-->
    <!--<h3>Symbol</h3>-->
    <div class="well well-sm col-md-4 col-md-offset-4">
        <div class="input-group">
            <input id="symbol" type="text" 
                   class="col-md-12 form-control" placeholder="Symbol..." 
                   autocomplete="off" />
            <span class="input-group-btn">
                <button class="btn btn-default" type="button"><i class="fa fa-search"></i>
                </button>
            </span>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-6 col-md-4 col-md-offset-2">
        <div class="input-group">
            <input type="text" id="fromDate" class="form-control datepicker" placeholder="From Date" >
            <span class="input-group-btn">
                <button class="btn btn-default" type="button" ><i class="glyphicon glyphicon-calendar"></i>
                </button>
            </span>
        </div>
    </div>
    <div class="col-sm-6 col-md-4">
        <div class="input-group">
            <input type="text" id="toDate" class="form-control datepicker" placeholder="To Date" >
            <span class="input-group-btn">
                <button class="btn btn-default" type="button"><i class="glyphicon glyphicon-calendar"></i>
                </button>
            </span>
        </div>
    </div>
</div>
<script type="text/javascript">
$(function() {

    $(".datepicker").datepicker({ dateFormat: 'dd-mm-yy' });
    
    var $fromDate = new Date();
    $fromDate.setMonth($fromDate.getMonth() - 6);
    $("#fromDate").datepicker("setDate", $fromDate);

    $("#toDate").datepicker("setDate", new Date());

});
</script>
</div>

<!--        <div class="form-group input-group">
            <input type="text" class="form-control">
            <span class="input-group-btn">
                <button class="btn btn-default" type="button"><i class="fa fa-search"></i>
                </button>
            </span>
        </div>-->
</div>
<!--</div>-->
</div>

<script>
    $(function() {

        function displayResult(item) {
            $('.alert').show().html('You selected <strong>' + item.value + '</strong>: <strong>' + item.text + '</strong>');
        }

        $.get("{{url('getAllSymbol')}}", function(data) {
            $("#symbol").typeahead({source: data, onSelect: displayResult});
        }, 'json');

    });
</script>

@stop

