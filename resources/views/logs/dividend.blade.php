@extends('admin.layouts.template')

@section('stylesheet')
<link href="{{asset('assets/bootstrap-table-master/dist/bootstrap-table.css')}}" rel="stylesheet" type="text/css"/>
<!--<link href="{{asset('assets/bootstrap-table-filter-master/src/bootstrap-table-filter.css')}}" rel="stylesheet" type="text/css"/>-->
<!--<link href="../../../public/assets/bootstrap-table-filter-master/src/bootstrap-table-filter.css" rel="stylesheet" type="text/css"/>-->

<!--<link rel="stylesheet" href="https://rawgit.com/lukaskral/bootstrap-table/feature_lukaskral_bootstrap_table_filter_integration/dist/bootstrap-table.min.css">-->
@stop

@section('content')

<div class="panel-body">
    <div class="row">
        <form role="form" action="{{url('logs/dividend')}}" method="post" id="formSearch">
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
                                <button type="button" class="btn btn-danger" id="clearButton">
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
        
        <div id="filter-bar">
        </div>
        <table id="tbl" data-toggle="table" 
                data-url="{{url('logs/dividend/getData')}}"
                data-sort-name="DUE_DATE" data-sort-order="desc"
                data-show-columns="true" data-id-field="id" 
                data-show-refresh="true" data-search="true"
                data-pagination="true"  data-row-style="rowStyle"
                data-row-attributes="rowAttributes" 
                data-toolbar="#filter-bar"
                data-query-params="queryParams"
                >
            <thead>
             <tr>
                 <th data-field="ID" data-halign="center" data-align="right" 
                     data-sortable="true" data-visible="false">ID</th>
                 <th data-field="SYMBOL" data-halign="center" data-align="center" 
                     data-sortable="true">ชื่อ</th>
                 <th data-field="VOLUME" data-halign="center" data-align="center" 
                     data-sortable="true">หน่วย</th>
                 <th data-field="PRICE" data-halign="center" data-align="center" 
                     data-sortable="true">ราคา</th>
                 <th data-field="NET_AMOUNT" data-halign="center" data-align="center" 
                     data-sortable="true">ราคาสุทธิ</th>
                 <th data-field="DATE" data-halign="center" data-align="center" 
                     data-sortable="true">วันที่</th>
                 <th data-field="DUE_DATE" data-halign="center" data-align="center" 
                     data-sortable="true">วันที่จ่าย</th>
                 <th data-field="BROKER_NAME" data-halign="center" data-align="center" 
                     data-sortable="true">โบรค</th>
                 <!--<th data-field="TOTAL" data-halign="center" data-align="center" data-sortable="true">รวม</th>-->
                 <th data-field="RESULT_PERCENT" data-halign="center" data-align="center" 
                     data-sortable="true">%</th>
             </tr>
             </thead>
         </table>

<!--        <table id="table-pagination" data-url="{{url('logs/dividens/getData')}}" data-height="400" data-pagination="true" data-search="true">
            <thead>
                <tr>
                    <th data-field="state" data-checkbox="true"></th>
                    <th data-field="id" data-align="right" data-sortable="true">Item ID</th>
                    <th data-field="name" data-align="center" data-sortable="true">Item Name</th>
                    <th data-field="price" data-sortable="true" data-sorter="priceSorter">Item Price</th>
                </tr>
            </thead>
        </table>-->
    </div>
    
</div>



@stop
@section('scripts')
<script src="{{asset('/assets/bower_components/typeahead/js/bootstrap3-typeahead.js')}}"></script>
<script src="{{asset('/assets/bootstrap-table-master/dist/bootstrap-table.js')}}"></script>
<script src="{{asset('/assets/js/logs-dividend.js')}}" type="text/javascript"></script>
<!--<script src="https://rawgit.com/lukaskral/bootstrap-table/feature_lukaskral_bootstrap_table_filter_integration/src/bootstrap-table.js"></script>-->
<!--<script src="{{asset('/assets/bootstrap-table-master/dist/extensions/filter/bootstrap-table-filter.js')}}"></script>
<script src="{{asset('/assets/bootstrap-table-master/dist/extensions/filter/ext/bs-table.js')}}"></script>-->

<!--<script src="{{asset('/assets/bootstrap-table-filter-master/src/bootstrap-table-filter.js')}}"></script>
<script src="{{asset('/assets/bootstrap-table-filter-master/src/ext/bs-table.js')}}"></script>-->

<script type="text/javascript">

var $getAllSymbol = "{{url('service/single/getAllSymbol')}}";
//var $getAllBroker = "{{url('service/single/getAllBroker')}}";
//    var $getSingleStock = "{{url('getSingleStock')}}";

</script>

</script>
@stop


