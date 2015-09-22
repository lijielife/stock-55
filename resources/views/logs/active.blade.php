@extends('admin.layouts.template')

@section('stylesheet')
<link href="{{asset('assets/css/logs-active.css')}}" rel="stylesheet" type="text/css"/>
@stop


@section('content')
                                    
<div class="panel-body">


    <div class="row">
        <!--<form method="POST" action="http://localhost/stock/public/logs/active" accept-charset="UTF-8">-->
            <!--<input name="_token" type="hidden" value="UuEwgy6B1xNrKWOEYRl5eW1FWUfAuKu8jlNRGGiO">-->
        <form role="form" action="{{url('logs/active')}}" method="post" id="formSearch">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
<!--            <input type="hidden" name="symbol" value="test" id="subSymbol">
            <input type="hidden" name="broker" value="testb" id="subBroker">-->
            <fieldset >
                <div class="row">
                    <div class="form-group col-md-6 col-md-offset-2">
                        <div class="input-group">
                            <input id="symbol" type="text" name="symbol" 
                                   class="form-control" placeholder="Symbol..." 
                                   autocomplete="off" value="{{$symbolName}}"/>
                            <span class="input-group-btn">
<!--                                <div class="btn-group" style="width: 100px;" role="group">
                                    <button type="button" class="btn btn-default dropdown-toggle btn-block text-right" 
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Broker
                                        <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu" id="brokerMenu">
                                        <li><a href="#" value="">All</a></li>
                                    </ul>
                                </div>-->
                                <div class="btn-group">
                                    <select class="selected form-control" id="brokerMenu" name="broker">
                                        <option value="">All</option>
                                        @if($brokers)
                                        @foreach($brokers as $broker)
                                            <option value="{{$broker->ID}}" 
                                                    {{ ($broker->ID == $brokerId) ? "selected" : "" }}
                                                >{{$broker->BROKER_NAME}}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                                <button class="btn btn-success" type="submit" id="searchButton">
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
        @foreach($stocks as $broker => $symbols)
        <h3 class="accordion-header ui-accordion-header ui-helper-reset ui-state-default ui-accordion-icons ui-corner-all">
            {{$broker}}</h3>
        <div class="ui-accordion-content ui-helper-reset ui-widget-content ui-corner-bottom">


            @if($symbols)
            @foreach($symbols as $symbol => $dataObjs)
            <h3 class="accordion-sub-header ui-accordion-header ui-helper-reset ui-sub-state-default ui-accordion-sub-icons ui-corner-all ui-accordion-sub">
                <span class="col-md-2 col-sm-3 col-xs-3">
                    {{$symbol}} 
                </span>
                <small> 
                    {{$dataObjs[$objVolKey]}}   
                </small>
            </h3>
            <div class="ui-accordion-content ui-helper-reset ui-sub-widget-content ui-corner-bottom ui-accordion-sub">
                <div class="col-md-10 col-md-offset-1 col-md-left">
                    <div class="panel panel-primary panel-clear-radius">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-center info col-md-4"> 
                                            <h5>ซื้อ
                                                <small>({{$dataObjs[$objVolBuyKey]}})</small>
                                            </h5> 
                                        </th>
                                        <th class="text-center price col-md-4">
                                            <h5>ราคา
                                                <small> 
                                                    
                                                </small>
                                            </h5>
                                        </th>
                                        <th class="text-center danger col-md-4">
                                            <h5>ขาย
                                                <small>({{$dataObjs[$objVolSellKey]}})</small>
                                            </h5>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($dataObjs[$objArrKey] as $data)
                                    <tr class="text-right">
                                        <td class="info">{{($data->VOLUME_BUY == 0 ? '' : $data->VOLUME_BUY)}}</td>
                                        <td class="text-center price">
                                            <h5>
                                                <span class="small">({{$data->PRICE_SRC - (($data->PRICE_SRC / 100) * 3) }})</span>
                                                    {{number_format($data->PRICE_SRC, 2, '.', '')}}
                                                <span class="small">({{$data->PRICE_SRC + (($data->PRICE_SRC / 100) * 3) }})</span>
                                            </h5>
                                        </td>
                                        <td class="danger">{{($data->VOLUME_SELL == 0 ? '' : $data->VOLUME_SELL)}}</td>
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
            </div>

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
//var $getAllBroker = "{{url('service/single/getAllBroker')}}";
//    var $getSingleStock = "{{url('getSingleStock')}}";

</script>

</script>
@stop


