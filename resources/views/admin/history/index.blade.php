@extends('admin.layouts.template')

@section('content')
<form action="{{url('/admin/user/action')}}" method="post">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <div class="row page-header">
        <div class="col-sm-6">
            <h3 class="">Get History</h3>
        </div>
        <!--		<div class="col-sm-6 text-right padding-top-20">
                                <a class="btn btn-success" href="{{url('admin/user/form')}}" title="Add new User"><i class="fa fa-plus-square" ></i> New</a>
                                <button class="btn btn-danger del" type="submit" name = "delete" title="Delete Multiple Users"><i class="fa fa-trash-o" ></i> Delete</button>
                        </div>-->
        <!-- /.col-lg-12 -->
    </div>

    <!--        $symbol = $this->getSymbol();
            $resolution = $this->getResolution();
            $from = $this->getFrom();
            $to = $this->getTo();-->

    <div class="row">
        <div class="col-sm-1">
            <h5 style="font-weight: bold">Symbol</h5>
        </div>
        <div class="col-sm-1">
            <h5 class="">{{$respone->obj->getSymbol()}}</h5>
        </div>

        <div class="col-sm-1">
            <h5 style="font-weight: bold">Resolution</h5>
        </div>
        <div class="col-sm-1">
            <h5 class="">{{$respone->obj->getResolution()}}</h5>
        </div>

        <div class="col-sm-1">
            <h5 style="font-weight: bold">From</h5>
        </div>
        <div class="col-sm-3">
            <h5 class="">{{$respone->obj->getFrom()}} ({{date("Y-m-d",$respone->obj->getFrom())}})</h5>
        </div>

        <div class="col-sm-1">
            <h5 style="font-weight: bold">To</h5>
        </div>
        <div class="col-sm-3">
            <h5 class="">{{$respone->obj->getTo()}} ({{date("Y-m-d",$respone->obj->getTo())}})</h5>
        </div>
    </div>

    <div class="row">

        <div class="col-sm-1">
            <h5 style="font-weight: bold">Data </h5>
        </div>
        <div class="col-sm-1">
            <h5 class="">{{$respone->count}}</h5>
        </div>
        <div class="col-sm-1">
            <h5 style="font-weight: bold">Rows</h5>
        </div>

    </div>

</form>
<!-- /.row -->
<div class="panel panel-default">
    <div class="panel-body">
        <div class="dataTable_wrapper">
            <table class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Time</th>
                        <!--<th><input type="checkbox" id="checkAll"/></th>-->
                        <th>Open</th>
                        <th>High</th>
                        <th>Low</th>
                        <th>Close</th>
                        <th>Volume (MB)</th>
                    </tr>
                </thead>
                <tbody>
                    @if($respone)
                    @if($respone->data)
                    @foreach($respone->data as $data)
                    <tr>
                        <td>{{$data->time}}</td>
                        <td>{{$data->open}}</td>
                        <td>{{$data->high}}</td>
                        <td>{{$data->low}}</td>
                        <td>{{$data->close}}</td>
                        <td>{{$data->volume}}</td>
                    </tr>
                    @endforeach
                    @endif
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@stop
