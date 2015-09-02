@extends('admin.layouts.template')

@section('stylesheet')
<link href="{{asset('assets/css/logs-active.css')}}" rel="stylesheet" type="text/css"/>
@stop


@section('content')

<div class="panel-body">


    <div class="row">
        <form role="form">
            <fieldset >
                <div class="row">
                    <div class="form-group col-md-6 col-md-offset-3">
                        <div class="input-group">
                            <input id="symbol" type="text" 
                                   class="col-md-12 form-control" placeholder="Symbol..." 
                                   autocomplete="off" value=""/>
                            <span class="input-group-btn">
                                <div class="btn-group" style="width: 100px;" role="group">
                                    <button type="button" class="btn btn-default dropdown-toggle btn-block text-right" 
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Broker
                                        <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu" id="brokerMenu">
                                        <li><a href="#" value="0">-</a></li>
                                    </ul>
                                </div>
                                <button class="btn btn-success" type="button" id="searchButton">
                                    <i class="fa fa-search"></i>
                                </button>
                                <button type="button" class="btn btn-danger">
                                    <i class="glyphicon glyphicon-trash"></i>
                                </button>
                            </span>
                        </div>
                    </div>
                </div>
            </fieldset>
        </form>
    </div>

    
    
    <button type="button" class="btn accordion-expand-holder accordion-expand-all" 
            style="position: fixed; top: 55px; right: 15px">
        <i class="glyphicon glyphicon-plus"></i>
    </button>

<!--    <p class="accordion-expand-holder">
        <a class="accordion-expand-all" href="#">Expand all</a>
    </p>-->

    <div id="accordion" class="ui-accordion ui-widget ui-helper-reset"> 


        @if($stocks)
        @foreach($stocks as $stock => $sides)
        <h3 class="accordion-header ui-accordion-header ui-helper-reset ui-state-default ui-accordion-icons ui-corner-all">
            {{$stock}}</h3>
        <div class="ui-accordion-content ui-helper-reset ui-widget-content ui-corner-bottom">

            @if($sides)
            @foreach($sides as $side => $datas)

            @if($side == "ซื้อ")      
            <div class="col-md-6 col-md-left">
                <div class="panel panel-primary panel-clear-radius">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr class="info">
                                    <th class="text-center">จำนวน</th>
                                    <th class="text-center">ราคา</th>
                                    <th class="text-center">รวม</th>
                                    <th class="text-center">วันที่</th>
                                    <th class="text-center">โบรค</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($datas as $data)
                                <tr class="text-right">
                                    <td>{{$data->volume}}</td>
                                    <td>{{$data->price}}</td>
                                    <td>{{$data->net_amount}}</td>
                                    <td>{{$data->date}}</td>
                                    <td>{{$data->broker}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- /.table-responsive -->
                </div>
                <!-- /.panel -->
            </div>
            <!-- /.col-md-6 -->

            @elseif($side == "ขาย")

            <div class="col-md-6 col-md-right">
                <div class="panel panel-red panel-clear-radius">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr class="danger">
                                    <th class="text-center">จำนวน</th>
                                    <th class="text-center">ราคา</th>
                                    <th class="text-center">รวม</th>
                                    <th class="text-center">วันที่</th>
                                    <th class="text-center">โบรค</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($datas as $data)
                                <tr class="text-right">
                                    <td>{{$data->volume}}</td>
                                    <td>{{$data->price}}</td>
                                    <td>{{$data->net_amount}}</td>
                                    <td>{{$data->date}}</td>
                                    <td>{{$data->broker}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- /.table-responsive -->
                </div>
                <!-- /.panel -->
            </div>
            <!-- /.col-md-6 -->

            @endif

            @endforeach
            @endif
        </div>

        @endforeach
        @endif

    </div>

</div>


@stop

@section('scripts')
<script src="{{asset('/assets/bower_components/typeahead/js/bootstrap3-typeahead.js')}}"></script>
<script src="{{asset('/assets/js/logs-active.js')}}" type="text/javascript"></script>

<script type="text/javascript">

var $getAllSymbol = "{{url('service/single/getAllSymbol')}}";
var $getAllBroker = "{{url('service/single/getAllBroker')}}";
//    var $getSingleStock = "{{url('getSingleStock')}}";

</script>

</script>
@stop


