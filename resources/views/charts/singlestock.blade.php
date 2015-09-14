@extends('admin.layouts.template')
@section('content')

<!--<link href="{{asset('/assets/bower_components/typeahead/css/typeaheadjs.css')}}" rel="stylesheet">-->

        <!--<script src="https://google-code-prettify.googlecode.com/svn/loader/run_prettify.js"></script>-->

<!--<div class="row">
    <div class="alert alert-block alert-success"></div>
</div>-->
<div class="panel-body">
    <form role="form">
        <fieldset >

            <div class="row">

                <div class="form-group col-md-4 col-md-offset-4">
                    <div class="input-group">
                        <input id="symbol" type="text" 
                               class="col-md-12 form-control" placeholder="Symbol..." 
                               autocomplete="off" value="SET"/>
                        
                        <span class="input-group-btn">
                            <div class="btn-group">
                                <select class="selected form-control" id="timeFrame">
                                    <option value="1">D</option>
                                    <option value="2">W</option>
                                    <option value="3">M</option>
                                </select>
                            </div>
                            <button class="btn btn-default" type="button" id="searchButton"><i class="fa fa-search"></i>
                            </button>
                        </span>

                    </div>
                </div>

            </div>

            <div class="row">
                <div class="form-group col-sm-6 col-md-4 col-md-offset-2">
                    <div class="input-group">
                        <input type="text" id="fromDate" class="form-control datepicker" placeholder="From Date" >
                        <span class="input-group-btn">
                            <button class="btn btn-default" type="button" ><i class="glyphicon glyphicon-calendar"></i>
                            </button>
                        </span>
                    </div>
                </div>
                <div class="form-group col-sm-6 col-md-4">
                    <div class="input-group">
                        <input type="text" id="toDate" class="form-control datepicker" placeholder="To Date" >
                        <span class="input-group-btn">
                            <button class="btn btn-default" type="button"><i class="glyphicon glyphicon-calendar"></i>
                            </button>
                        </span>
                    </div>
                </div>
            </div>
        </fieldset>
    </form>
</div>



<div class="row">
    <div id="container" style="height: 900px; min-width: 410px"></div>
</div>


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


@stop


@section('scripts')



<script src="{{asset('/assets/bower_components/typeahead/js/bootstrap3-typeahead.js')}}"></script>
<script src="{{asset('/assets/bower_components/typeahead/js/bootstrap3-typeahead.js')}}"></script>


<!--<script src="http://code.highcharts.com/stock/highstock.js"></script>
<script src="http://code.highcharts.com/stock/modules/exporting.js"></script>-->

<!--<script src="http://code.highcharts.com/stock/highstock.js"></script>-->
<!--<script src="https://rawgithub.com/laff/technical-indicators/master/technical-indicators.src.js"></script>-->

<script src="{{asset('/assets/dist/js/highchart-custom.js')}}" type="text/javascript"></script>
<script src="http://code.highcharts.com/stock/modules/exporting.js"></script>
<script src="{{asset('/assets/dist/js/highcharts-fn.js')}}" type="text/javascript"></script>
<script src="{{asset('/assets/dist/js/highchart-theme.js')}}" type="text/javascript"></script>
<script src="{{asset('/assets/dist/js/highstock-plugin.js')}}" type="text/javascript"></script>


<script type="text/javascript">
var $getAllSymbol = "{{url('service/single/getAllSymbol')}}";
var $getSingleStock = "{{url('service/single/getSingleStock')}}";

</script>


<script src="{{asset('/assets/js/single-stock.js')}}" type="text/javascript"></script>

<!--<script src="{{asset('/assets/bower_components/typeahead/js/jquery.mockjax.js')}}"></script>-->
<!--<script src="{{asset('/assets/bower_components/typeahead/js/bootstrap-typeahead.js')}}"></script>-->

@stop