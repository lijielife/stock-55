@extends('admin.layouts.template')

@section('stylesheet')
<link href="{{asset('assets/css/logs-profile.css')}}" rel="stylesheet" type="text/css"/>
@stop


@section('content')

<div class="panel-body">
    <div class="row">
        <form role="form" action="{{url('logs/profile')}}" method="post" id="formSearch">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <fieldset >
                <div class="row">
                    <div class="form-group col-md-6 col-md-offset-2">
                        <div class="input-group">
                            <input id="symbol" type="text" name="symbol" 
                                   class="form-control" placeholder="Symbol..." 
                                   autocomplete="off" value="{{$symbolName}}"/>
                            <span class="input-group-btn">
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



    <!--    <button type="button" class="btn accordion-expand-holder accordion-expand-all" 
                style="position: fixed; top: 55px; right: 15px">
            <i class="glyphicon glyphicon-plus"></i>
        </button>-->

    <div class="row">
        <div class="table-responsive " style="margin-right: 10px;">
            <table class="table table-striped table-bordered table-hover  table-fixed" style="margin-bottom: 0px;">
                <thead>
                    <tr>
                        <th class="text-center">ID</th>
                        <!--<th class="text-center">คำสั่ง</th>-->
                        <!--<th class="text-center">ชื่อ</th>-->
                        <th class="text-center">หน่วย</th>
                        <th class="text-center">ราคา</th>
                        <th class="text-center">ราคาสุทธิ</th>
                        <th class="text-center">วันที่</th>
                        <!--<th class="text-center">วันครบกำหนด</th>-->
                        <th class="text-center">เหลือ</th>
                        <th class="text-center">มูลค่าหุ้น</th>
                        <th class="text-center">ผล</th>
                        <th class="text-center">%</th>
                        <th class="text-center">port index</th>
                        <th class="text-center">ราคาเฉลี่ย</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>



    <div class="row">
        <div class="col-lg-12">
            <div class="table-responsive " style="height: 750px;">
                <div id="bodyDiv" class="bodyDiv">
                    <div id="scrollbox3" class="scrollbox3">
                        <table class="table table-striped table-bordered table-hover  table-fixed">
                            <tbody>
                                @if($stocks)
                                @foreach($stocks as $stock)
                                {{-- */$classMatcher = (
                                            $stock->MATCHER <> null ? 'gray' :

                                            (
                                                $stock->SIDE_CODE == '001' ? 'info' : 
                                                (
                                                    $stock->SIDE_CODE == '002'? 'danger' : 'success'
                                                )
                                            )
                                        );/* --}}
                                {{-- */$classSide = (
                                                $stock->SIDE_CODE == '001' ? 'info' : 
                                                (
                                                    $stock->SIDE_CODE == '002'? 'danger' : 'success'
                                                )
                                        );/* --}}

                                <tr class="text-right {{$classSide}}">
                                    <td class="text-center {{$classSide}}">{{$stock->ID}}</td>
                                    <!--<td class="text-center ">{{$stock->SIDE_NAME}}</td>-->
                                    <!--<td class="text-center ">{{$stock->SYMBOL}}</td>-->
                                    <td class="text-center {{$classMatcher}}">{{$stock->VOLUME}}</td>
                                    <td class="text-center {{$classMatcher}}">{{$stock->PRICE}}</td>
                                    <td class="text-center {{$classMatcher}}">{{$stock->NET_AMOUNT}}</td>
                                    <td class="text-center {{$classMatcher}}">{{(new DateTime($stock->DATE))->format('Ymd')}}</td>
                                    <!--<td class="text-center ">{{$stock->DUE_DATE}}</td>-->
                                    <td class="text-center {{$classMatcher}}">{{$stock->TOTAL}}</td>
                                    <td class="text-center {{$classMatcher}}">{{$stock->VALUE}}</td>
                                    <td class="text-center {{$classMatcher}}">{{$stock->RESULT}}</td>
                                    <td class="text-center {{$classMatcher}}">{{$stock->RESULT_PERCENT}}</td>
                                    <td class="text-center {{$classMatcher}}">{{$stock->PORT_INDEX}}</td>
                                    <td class="text-center {{$classMatcher}}">{{$stock->AVG_PRICE}}</td>

                                </tr>

                                @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



@stop
@section('scripts')
<script src="{{asset('/assets/bower_components/typeahead/js/bootstrap3-typeahead.js')}}"></script>
<script src="{{asset('/assets/js/logs-profile.js')}}" type="text/javascript"></script>

<script type="text/javascript">

var $getAllSymbol = "{{url('service/single/getAllSymbol')}}";
//var $getAllBroker = "{{url('service/single/getAllBroker')}}";
//    var $getSingleStock = "{{url('getSingleStock')}}";

</script>

</script>
@stop


