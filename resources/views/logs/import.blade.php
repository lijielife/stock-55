@extends('admin.layouts.template')
@section('stylesheet')
<link href="{{asset('assets/css/admin-upload.css')}}" rel="stylesheet" type="text/css"/>
@stop

@section('content')
<form action="{{url('/logs/import/action')}}" method="post" enctype="multipart/form-data">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <div class="row page-header">
        <div class="col-sm-12">
            <h1 class="">Basic Uploader</h1>
        </div>
        <div class="col-sm-6 text-right padding-top-20">
            <input type="file" name="uploader" id="uploader" />
        </div>

        <div class="col-sm-6 text-right padding-top-20">
            <button class="btn btn-success" type="submit" name = "btn-upload" title="Upload image"><i class="fa fa-upload" ></i> Upload</button>
            <button class="btn btn-danger del" id="btnDelete" type="button" name = "btn-delete" title="Delete Multiple image"><i class="fa fa-trash-o" ></i> Delete</button>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="dataTable_wrapper">
                <div class="row">
                    <div class="col-xs-3 gallery">
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<script type="text/javascript">
    $("#btnDelete").click(function(){
        $("#uploader").val('');
    });
</script>
@stop



