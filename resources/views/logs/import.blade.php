@extends('admin.layouts.template')
@section('stylesheet')
<link href="{{asset('assets/css/import.css')}}" rel="stylesheet" type="text/css"/>
@stop

@section('content')
<form action="{{url('/logs/import/action')}}" method="post" enctype="multipart/form-data">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <div class="row page-header">
        <div class="col-sm-12">
            <h1 class="">Excel Import</h1>
        </div>
        <div class="col-sm-offset-1 col-sm-5 text-center padding-top-20">
            <input type="file" name="uploader" id="uploader" />
        </div>

        <div class="col-sm-6 text-center padding-top-20">
            <button class="btn btn-success" type="submit" name = "btn-upload" title="Upload image"><i class="fa fa-upload" ></i> Upload</button>
            <button class="btn btn-danger del" id="btnDelete" type="button" name = "btn-delete" title="Delete Multiple image"><i class="fa fa-trash-o" ></i> Delete</button>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!--<div class="panel panel-default">-->
    <div class="panel-body">
        <div class="dataTable_wrapper">

            <div class="row">
                <div class="table-responsive " style="margin-right: 10px;">
                    <table class="table table-striped table-bordered table-hover  table-fixed" style="margin-bottom: 0px;">
                        <thead>
                            <tr>
                                <th class="text-center">คำสั่ง</th>
                                <th class="text-center">ชื่อ</th>
                                <th class="text-center">จำนวนหน่วย</th>
                                <th class="text-center">ราคา</th>
                                <th class="text-center">ค่าคอม</th>
                                <th class="text-center">ราคารวม</th>
                                <th class="text-center">ราคาสุทธิ</th>
                                <th class="text-center">วันที่</th>
                                <th class="text-center">นายหน้า</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="table-responsive " style="height: 650px;">
                        <div id="bodyDiv" class="bodyDiv">
                            <div id="scrollbox3" class="scrollbox3">
                                <!--<div id="tablePlaceholder" class="grid_12 alpha omega">-->

                                <table class="table table-striped table-bordered table-hover  table-fixed">
<!--                                    <thead>
                                        <tr>
                                            <th class="text-center">คำสั่ง</th>
                                            <th class="text-center">ชื่อ</th>
                                            <th class="text-center">จำนวนหน่วย</th>
                                            <th class="text-center">ราคา</th>
                                            <th class="text-center">ค่าคอม</th>
                                            <th class="text-center">ราคารวม</th>
                                            <th class="text-center">ราคาสุทธิ</th>
                                            <th class="text-center">วันที่</th>
                                            <th class="text-center">นายหน้า</th>
                                        </tr>
                                    </thead>-->



                                    <tbody>
                                        @if($dataLogs)
                                        @foreach($dataLogs as $dataLog)
                                        <tr>
                                            <td class="text-right">{{$dataLog->side}}</td>
                                            <td class="text-right">{{$dataLog->symbol}}</td>
                                            <td class="text-right">{{$dataLog->volume}}</td>
                                            <td class="text-right">{{$dataLog->price}}</td>
                                            <td class="text-right">{{$dataLog->amount}}</td>
                                            <td class="text-right">{{$dataLog->vat}}</td>
                                            <td class="text-right">{{$dataLog->net_amount}}</td>
                                            <td class="text-right">{{$dataLog->date}}</td>
                                            <td class="text-right">{{$dataLog->broker}}</td>
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

    </div>
    <!--</div>-->
</div>
</form>

@stop



@section('scripts')
<script src="{{asset('assets/js/logs-import.js')}}" type="text/javascript" ></script>
@stop

