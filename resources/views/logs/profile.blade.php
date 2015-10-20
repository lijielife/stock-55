@extends('admin.layouts.template')

@section('stylesheet')
<link href="{{asset('assets/bootstrap-table-master/dist/bootstrap-table.css')}}" rel="stylesheet" type="text/css"/>

<!--<link href="{{asset('assets/css/logs-profile.css')}}" rel="stylesheet" type="text/css"/>-->
@stop


@section('content')

<div class="panel-body">
    <div class="row">
        <!--<form role="form" action="{{url('logs/profile')}}" method="post" id="formSearch">-->
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <fieldset >
            <div class="row">
                <div class="form-group col-md-4 col-md-offset-4">
                    <div class="input-group">
                        <input id="symbol" type="text" name="symbol" 
                               class="form-control" placeholder="Symbol..." 
                               autocomplete="off" value="{{$symbolName}}"/>
                        <span class="input-group-btn">
                            <div class="btn-group">
                                <select class="selected form-control" id="brokerMenu" name="broker">
                                    <option value="" selected>All</option>
                                    @if($brokers)
                                    @foreach($brokers as $broker)
                                    <option value="{{$broker->ID}}" 
                                            {{ ($broker->ID == $brokerId) ? "selected" : "" }}
                                        >{{$broker->BROKER_NAME}}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                            <button class="btn btn-success" type="button" id="searchButton">
                                <i class="fa fa-search"></i>
                            </button>
                            <button type="button" class="btn btn-danger"  id="cleanButton">
                                <i class="fa fa-times"></i>
                            </button>
                        </span>
                    </div>
                </div>
            </div>
        </fieldset>
        <!--</form>-->
    </div>



    <!--    <button type="button" class="btn accordion-expand-holder accordion-expand-all" 
                style="position: fixed; top: 55px; right: 15px">
            <i class="glyphicon glyphicon-plus"></i>
        </button>-->



    <div class="row">


        <!--<div id="filter-bar"> </div>-->

        <div id="transform-buttons" >
            <div class="columns-left btn-group pull-left form-group">
                
                <div class="input-group">
                    <input type="text" class="form-control" id="volume" placeholder="Volume" style="width:80px;"/>
                    <input type="text" class="form-control" id="price" placeholder="Price" style="width:80px;"/>
                    <span class="input-group-btn" style="width: 0px;">
                        <button class="btn btn-default btn-group" id="addBuyButton">
                            <i class="fa fa-plus-circle" style="color: blue;"></i>
                        </button>
                        <button class="btn btn-default btn-group" id="addSellButton">
                            <i class="fa fa-plus-circle" style="color: red;"></i>
                        </button>
                        <button class="btn btn-default btn-group" id="addDivButton">
                            <i class="fa fa-plus-circle" style="color: yellowgreen;"></i>
                        </button>
                        <button class="btn btn-default btn-group" id="removeButton">
                            <i class="fa fa-minus-circle"></i>
                        </button>
                        <button class="btn btn-default btn-group" id="saveButton">
                            <i class="fa fa-save" style="color: green;"></i>
                        </button>
                        <button class="btn btn-default btn-group" id="deleteTestButton">
                            <i class="fa fa-trash-o" style="color: red;"></i>
                        </button>
                        <button class="btn btn-default btn-group" id="loadPriceButton">
                            <i class="fa fa-download"></i>
                        </button>
                        <button class="btn btn-default btn-group" id="matcherButton">
                            <i class="glyphicon glyphicon-transfer"></i> 
                            <span data-zh="转换" data-es="Transformar">Matcher</span>
                        </button>
                        <div class="keep-open btn-group" title="Columns">
                            <button type="button" class="btn btn-default dropdown-toggle"  id="events-result" disabled="true">
                                Result
                            </button>
                        </div>
                    </span>
                </div>
            </div>



        </div>
        <!--                data-row-attributes="rowAttributes" -->
        <table id="tbl" data-toggle="table"  data-toolbar="#transform-buttons"
               data-url="{{url('logs/profile/getData')}}"
               data-sort-name="DUE_DATE" data-sort-order="desc"
               data-show-columns="true" data-id-field="id" 
               data-show-refresh="true" data-search="true"
               data-pagination="true"  
               data-toolbar="#filter-bar"
               data-query-params="queryParams"
               data-single-select="false"
               data-click-to-select="true"
               data-show-filter="false"
               >
            <thead>
                <tr>
                    <th data-field="state" data-checkbox="true"></th>
                    <th data-field="ID" data-halign="center" data-align="right" 
                        data-sortable="false" data-visible="false">ID</th>
                    <th data-field="DATE" data-halign="center" data-align="center" 
                        data-sortable="false" data-cell-style="cellStyleMatcher">วันที่</th>
                    <th data-field="SIDE_NAME" data-halign="center" data-align="center" 
                        data-sortable="false" data-cell-style="cellStyle" data-visible="false"
                        >คำสั่ง</th>
                    <th data-field="SYMBOL" data-halign="center" data-align="center" 
                        data-sortable="false" data-cell-style="cellStyle" data-visible="false"
                        >ชื่อ</th>
                    <th data-field="VOLUME" data-halign="center" data-align="right" 
                        data-sortable="false" data-cell-style="cellStyle" data-width="100"
                        data-formatter="numFormatter">หน่วย</th>
                    <th data-field="PRICE_IN_DAY" data-halign="center" data-align="right" 
                        data-sortable="false" data-visible="false" data-width="100"
                        data-formatter="numFormatter2">ราคาปิด</th>
                    <th data-field="PRICE" data-halign="center" data-align="right" 
                        data-sortable="false" data-width="100"
                        data-formatter="numFormatter2" >ราคา</th>
                    <th data-field="NET_AMOUNT" data-halign="center" data-align="right" 
                        data-sortable="false" data-formatter="numFormatter2">ราคาสุทธิ</th>
   <!--                 <th data-field="DUE_DATE" data-halign="center" data-align="center" 
                        data-sortable="false">วันที่จ่าย</th>-->
                    <th data-field="TOTAL" data-halign="center" data-align="right" 
                        data-sortable="false" data-formatter="numFormatter">เหลือ</th>
                    <th data-field="VALUE" data-halign="center" data-align="right" 
                        data-sortable="false" data-formatter="numFormatter2">มูลค่า</th>
                    <th data-field="RESULT" data-halign="center" data-align="right" 
                        data-sortable="false" data-cell-style="cellValueStyle"
                        data-formatter="numFormatter2">ผล</th>
                    <th data-field="RESULT_PERCENT" data-halign="center" data-align="right" 
                        data-sortable="false" data-cell-style="cellValueStyle"
                        data-formatter="numFormatter2">%</th>
                    <th data-field="PORT_INDEX" data-halign="center" data-align="right" 
                        data-sortable="false" data-cell-style="cellValuePercentStyle"
                        data-formatter="numFormatter2">port index</th>
                    <th data-field="AVG_PRICE" data-halign="center" data-align="right" 
                        data-sortable="false" data-cell-style="cellValueAvgStyle"
                        data-formatter="numFormatter4">ราคาเฉลี่ย</th>
                    <th data-field="BROKER_NAME" data-halign="center" data-align="center" 
                        data-sortable="false" data-visible="false">โบรค</th>
                    <th data-field="MATCHER" data-halign="center" data-align="center" 
                        data-sortable="false" data-visible="false">Matcher</th>

                </tr>
            </thead>
        </table>
    </div>

</div>



@stop
@section('scripts')
<script src="{{asset('/assets/bower_components/typeahead/js/bootstrap3-typeahead.js')}}"></script>
<script src="{{asset('/assets/bootstrap-table-master/dist/bootstrap-table.js')}}"></script>

<script src="{{asset('/assets/bootstrap-table-master/dist/extensions/filter/bootstrap-table-filter2.js')}}"></script>
<script src="{{asset('/assets/bootstrap-table-master/dist/extensions/filter/bootstrap-table-filter.js')}}"></script>

<script src="{{asset('/assets/js/logs-profile.js')}}" type="text/javascript"></script>

<script type="text/javascript">

var $getAllSymbol = "{{url('service/single/getAllSymbol')}}";
var $loadUrl= "{{url('history/loadData')}}";
var $saveDataUrl = "{{url('logs/profile/save')}}";
var $deleteTestUrl = "{{url('logs/profile/deleteTest')}}";
//var $getAllBroker = "{{url('service/single/getAllBroker')}}";
//    var $getSingleStock = "{{url('getSingleStock')}}";

</script>

</script>
@stop


