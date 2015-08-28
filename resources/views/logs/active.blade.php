@extends('admin.layouts.template')
@section('content')


<script src="{{asset('/assets/bower_components/typeahead/js/bootstrap3-typeahead.js')}}"></script>
<script src="{{asset('/assets/bower_components/typeahead/js/bootstrap3-typeahead.js')}}"></script>


<!--<script src="http://code.highcharts.com/stock/highstock.js"></script>
<script src="http://code.highcharts.com/stock/modules/exporting.js"></script>-->

<!--<script src="http://code.highcharts.com/stock/highstock.js"></script>
<script src="http://code.highcharts.com/stock/modules/exporting.js"></script>-->

<!--<script src="{{asset('/assets/dist/js/highcharts-fn.js')}}" type="text/javascript"></script>
<script src="{{asset('/assets/dist/js/highchart-theme.js')}}" type="text/javascript"></script>-->


<script type="text/javascript">
//    var $getAllSymbol = "{{url('getAllSymbol')}}";
//    var $getSingleStock = "{{url('getSingleStock')}}";
</script>


<script src="{{asset('/assets/js/logs-active.js')}}" type="text/javascript"></script>

<!--<script src="{{asset('/assets/bower_components/typeahead/js/jquery.mockjax.js')}}"></script>-->
<!--<script src="{{asset('/assets/bower_components/typeahead/js/bootstrap-typeahead.js')}}"></script>-->
<!--<link href="{{asset('/assets/bower_components/typeahead/css/typeaheadjs.css')}}" rel="stylesheet">-->

        <!--<script src="https://google-code-prettify.googlecode.com/svn/loader/run_prettify.js"></script>-->

<!--<div class="row">
    <div class="col-lg-12">
        <h3 class="page-header">Logs Active</h3>
    </div>
     /.col-lg-12 
</div>-->

<div class="panel-body">

    <div class="row">
        <div class="col-lg-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Kitchen Sink
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Username</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>Mark</td>
                                    <td>Otto</td>
                                    <td>@mdo</td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Jacob</td>
                                    <td>Thornton</td>
                                    <td>@fat</td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>Larry</td>
                                    <td>the Bird</td>
                                    <td>@twitter</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.table-responsive -->
                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
        </div>
        <!-- /.col-lg-6 -->
        <div class="col-lg-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Basic Table
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Username</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>Mark</td>
                                    <td>Otto</td>
                                    <td>@mdo</td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Jacob</td>
                                    <td>Thornton</td>
                                    <td>@fat</td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>Larry</td>
                                    <td>the Bird</td>
                                    <td>@twitter</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.table-responsive -->
                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
        </div>
        <!-- /.col-lg-6 -->
    </div>
</div>


@stop

