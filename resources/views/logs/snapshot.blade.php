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
                                <button class="btn btn-success" type="button" id="searchButton">
                                    <i class="fa fa-search"></i>
                                </button>
                                <button type="button" class="btn btn-danger"  id="clearButton">
                                    <i class="glyphicon glyphicon-trash"></i>
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
         <!--data-toolbar="#transform-buttons"-->
<!--        <div id="transform-buttons" class="btn-group btn-default">
            <button class="btn btn-default" id="matcherButton">
                <i class="glyphicon glyphicon-transfer"></i> 
                <span data-zh="转换" data-es="Transformar">Matcher</span>
            </button>
        </div>-->
        <div id="filter-bar"> </div>

        
<!--                data-row-attributes="rowAttributes" -->
        <table id="tbl" data-toggle="table" 
                data-toolbar="#filter-bar"
                data-show-filter="true"
                
                data-url="{{url('logs/snapshot/getData')}}"
                data-show-columns="true" data-id-field="ID" 
                data-show-refresh="true" data-search="true"
                data-toolbar="#filter-bar"
                data-query-params="queryParams"
                data-single-select="false"
                data-click-to-select="true"
                data-show-footer="true"
                data-sort-name="SYMBOL" data-sort-order="asc"
                
                >
            <thead>
             <tr>
                 
                <th data-field="state" data-checkbox="true">#</th>
                 <th data-field="ID" data-halign="center" data-align="right" 
                     data-sortable="true" data-visible="false">ID</th>
<!--                 <th data-field="DATE" data-halign="center" data-align="center" 
                     data-sortable="true">วันที่</th>-->
                 <th data-field="SYMBOL" data-halign="center" data-align="left" 
                     data-sortable="true"data-footer-formatter="totalTextFormatter"
                     >ชื่อ</th>
                <th data-field="PRICE_IN_DAY" data-halign="center" data-align="right" 
                    data-sortable="true" data-formatter="numFormatter2">ราคาปิด</th>
<!--                 <th data-field="VOLUME" data-halign="center" data-align="center" 
                     data-sortable="true" data-cell-style="cellStyle">หน่วย</th>-->
<!--                 <th data-field="PRICE" data-halign="center" data-align="center" 
                     data-sortable="true">ราคา</th>-->
<!--                 <th data-field="NET_AMOUNT" data-halign="center" data-align="center" 
                     data-sortable="true">ราคาสุทธิ</th>-->
<!--                 <th data-field="DUE_DATE" data-halign="center" data-align="center" 
                     data-sortable="true">วันที่จ่าย</th>-->
                 <th data-field="TOTAL" data-halign="center" data-align="right" 
                     data-sortable="true" data-formatter="numFormatter">เหลือ</th>
                 <th data-field="VALUE" data-halign="center" data-align="right" 
                     data-sortable="true" data-formatter="numFormatter2"
                     data-footer-formatter="sumFormatter">มูลค่า</th>
                 <th data-field="RESULT" data-halign="center" data-align="right" 
                     data-sortable="true" data-cell-style="cellValueStyle"
                     data-formatter="numFormatter2"
                     data-footer-formatter="sumFormatter">ผล</th>
                 <th data-field="RESULT_PERCENT" data-halign="center" data-align="right" 
                     data-sortable="true" data-cell-style="cellValueStyle"
                     data-formatter="numFormatter2"
                     data-footer-formatter="percentFormatter">%</th>
                 <th data-field="PORT_INDEX" data-halign="center" data-align="right" 
                     data-sortable="true" data-cell-style="cellPortIndexStyle"
                     data-formatter="numFormatter2">port index</th>
                 <th data-field="AVG_PRICE" data-halign="center" data-align="right" 
                     data-sortable="true" data-cell-style="cellValueAvgStyle"
                     data-formatter="numFormatter4">ราคาเฉลี่ย</th>
                 <th data-field="BROKER_NAME" data-halign="center" data-align="right" 
                     data-sortable="true" data-visible="false">โบรค</th>
                 <th data-field="MATCHER" data-halign="center" data-align="right" 
                     data-sortable="true" data-visible="false">Matcher</th>
                 
             </tr>
             </thead>
         </table>
    </div>
    
</div>



@stop
@section('scripts')
<!--<script src="../../../public/assets/bootstrap-table-master/dist/extensions/filter-control/bootstrap-table-filter-control.js" type="text/javascript"></script>-->
<script src="{{asset('/assets/bower_components/typeahead/js/bootstrap3-typeahead.js')}}"></script>
<script src="{{asset('/assets/bootstrap-table-master/dist/bootstrap-table.js')}}"></script>
<script src="{{asset('/assets/bootstrap-table-master/dist/extensions/filter/bootstrap-table-filter2.js')}}"></script>
<script src="{{asset('/assets/bootstrap-table-master/dist/extensions/filter/bootstrap-table-filter.js')}}"></script>
<script src="{{asset('/assets/js/logs-snapshot.js')}}" type="text/javascript"></script>
<script type="text/javascript">

var $getAllSymbol = "{{url('service/single/getAllSymbol')}}";

var $profileUrl= "{{url('logs/profile')}}";
//var $getAllBroker = "{{url('service/single/getAllBroker')}}";
//    var $getSingleStock = "{{url('getSingleStock')}}";

</script>

@stop


