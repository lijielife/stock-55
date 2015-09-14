@extends('admin.layouts.template')

@section('stylesheet')
<link href="{{asset('assets/bootstrap-table-master/dist/bootstrap-table.css')}}" rel="stylesheet" type="text/css"/>
@stop

@section('content')

<div class="panel-body">
    <div class="row">
        <form role="form" action="{{url('logs/dividend')}}" method="post" id="formSearch">
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
        
        <table data-toggle="table" data-url="{{url('logs/dividen/getData')}}">
             <thead>
             <tr>
                 <th data-field="name">Name</th>
                 <th data-field="stargazers_count">Stars</th>
                 <th data-field="forks_count">Forks</th>
                 <th data-field="description">Description</th>
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
<!--<script src="{{asset('/assets/js/logs-dividend.js')}}" type="text/javascript"></script>-->

<script type="text/javascript">

var $getAllSymbol = "{{url('service/single/getAllSymbol')}}";
//var $getAllBroker = "{{url('service/single/getAllBroker')}}";
//    var $getSingleStock = "{{url('getSingleStock')}}";

</script>

</script>
@stop


