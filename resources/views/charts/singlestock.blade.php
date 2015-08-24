@extends('admin.layouts.template')
@section('content')


<script src="{{asset('/assets/bower_components/typeahead/js/bootstrap3-typeahead.js')}}"></script>
<script src="{{asset('/assets/bower_components/typeahead/js/jquery.mockjax.js')}}"></script>
<script src="{{asset('/assets/bower_components/typeahead/js/bootstrap-typeahead.js')}}"></script>
<!--<link href="{{asset('/assets/bower_components/typeahead/css/typeaheadjs.css')}}" rel="stylesheet">-->

        <!--<script src="https://google-code-prettify.googlecode.com/svn/loader/run_prettify.js"></script>-->


<div class="form-group">
    <label>Text Input with Placeholder</label>
    <input class="form-control" placeholder="Enter text">
</div>
<div class="row">
    <h1>Twitter Bootstrap Ajax Typeahead Plugin Demo</h1>
    <a href="https://github.com/biggora/bootstrap-ajax-typeahead" target="_blank">https://github.com/biggora/bootstrap-ajax-typeahead</a>
    <hr />
</div>
<div class="row">
    <div class="alert alert-block alert-success"></div>
</div>
<div class="row">
    <!-- Nav tabs -->
    <ul class="nav nav-tabs">
        <li class="active"><a href="#tab-demo1" data-toggle="tab">Demo #1</a></li>
        <li><a href="#tab-demo2" data-toggle="tab">Demo #2</a></li>
        <li><a href="#tab-demo3" data-toggle="tab">Demo #3</a></li>
        <li><a href="#tab-demo4" data-toggle="tab">Demo #4</a></li>
        <li><a href="#tab-demo5" data-toggle="tab">Demo #5</a></li>
        <li><a href="#tab-demo6" data-toggle="tab">Demo #6</a></li>
        <li><a href="#tab-demo7" data-toggle="tab">Demo #7</a></li>
    </ul>
    <!-- Tab panes -->
    <div class="tab-content">
        <div class="tab-pane active" id="tab-demo1">
            <h3>Demo #1</h3>
            <div class="well col-md-5">
                <input id="demo1" type="text" class="col-md-12 form-control" placeholder="Search cities..." autocomplete="off" />
            </div>
            <div class="col-md-7">
                <pre class="prettyprint">

    $('#demo1').typeahead({
        source: [
            { id: 1, name: 'Toronto' },
            { id: 2, name: 'Montreal' },
            { id: 3, name: 'New York' },
            { id: 4, name: 'Buffalo' },
            { id: 5, name: 'Boston' },
            { id: 6, name: 'Columbus' },
            { id: 7, name: 'Dallas' },
            { id: 8, name: 'Vancouver' },
            { id: 9, name: 'Seattle' },
            { id: 10, name: 'Los Angeles' }
        ]
    });
                </pre>
            </div>
        </div>
        <div class="tab-pane" id="tab-demo2">
            <h3>Demo #2</h3>
            <div class="well col-md-5">
                <input id="demo2" type="text" class="col-md-12 form-control" placeholder="Search cities..." autocomplete="off" />
            </div>
            <div class="col-md-7">
                <pre class="prettyprint">

    $('#demo2').typeahead({
        source: [
            { ID: 1, Name: 'Toronto' },
            { ID: 2, Name: 'Montreal' },
            { ID: 3, Name: 'New York' },
            { ID: 4, Name: 'Buffalo' },
            { ID: 5, Name: 'Boston' },
            { ID: 6, Name: 'Columbus' },
            { ID: 7, Name: 'Dallas' },
            { ID: 8, Name: 'Vancouver' },
            { ID: 9, Name: 'Seattle' },
            { ID: 10, Name: 'Los Angeles' }
        ],
        display: 'Name',
        val: 'ID'
    });
                </pre>
            </div>
        </div>
        <div class="tab-pane" id="tab-demo3">
            <h3>Demo #3</h3>
            <div class="well col-md-5">
                <input id="demo3" type="text" class="col-md-12 form-control" placeholder="Search cities..." autocomplete="off" />
            </div>
            <div class="col-md-7">
                <pre class="prettyprint">

    $('#demo3').typeahead({
        source: [
            { id: 1, full_name: 'Toronto', first_two_letters: 'To' },
            { id: 2, full_name: 'Montreal', first_two_letters: 'Mo' },
            { id: 3, full_name: 'New York', first_two_letters: 'Ne' },
            { id: 4, full_name: 'Buffalo', first_two_letters: 'Bu' },
            { id: 5, full_name: 'Boston', first_two_letters: 'Bo' },
            { id: 6, full_name: 'Columbus', first_two_letters: 'Co' },
            { id: 7, full_name: 'Dallas', first_two_letters: 'Da' },
            { id: 8, full_name: 'Vancouver', first_two_letters: 'Va' },
            { id: 9, full_name: 'Seattle', first_two_letters: 'Se' },
            { id: 10, full_name: 'Los Angeles', first_two_letters: 'Lo' }
        ],
        displayField: 'full_name'
    });
                </pre>
            </div>
        </div>
        <div class="tab-pane" id="tab-demo4">
            <h3>Demo #4</h3>
            <div class="well col-md-5">
                <input id="demo4" type="text" class="col-md-12 form-control" placeholder="Search cities..." autocomplete="off" />
            </div>
            <div class="col-md-7">
                <pre class="prettyprint">

    $('#demo4').typeahead({
        ajax: '{{url('getAllSymbol')}}'
    });
                </pre>
            </div>
        </div>
        <div class="tab-pane" id="tab-demo5">
            <h3>Demo #5</h3>
            <div class="well col-md-5">
                <input id="demo5" type="text" class="col-md-12 form-control" placeholder="Search cities..." autocomplete="off" />
            </div>
            <div class="col-md-7">
                <pre class="prettyprint">

    $('#demo5').typeahead({
        ajax: { 
                url: '{{url('getAllSymbol')}}',
                triggerLength: 1 
              }
    });
                </pre>
            </div>
        </div>
        <div class="tab-pane" id="tab-demo6">
            <h3>Demo #6</h3>
            <div class="well col-md-5">
                <input id="demo6" type="text" class="col-md-12 form-control" placeholder="Search cities..." autocomplete="off" />
            </div>
            <div class="col-md-7">
                <pre class="prettyprint">

    $('#demo6').typeahead({
        source: ['Toronto',
                 'Montreal',
                 'New York', 
                 'Buffalo', 
                 'Boston', 
                 'Columbus', 
                 'Dallas', 
                 'Vancouver', 
                 'Seattle', 
                 'Los Angeles']
    });
                </pre>
            </div>
        </div>

        <div class="tab-pane" id="tab-demo7">
            <h3>Demo #7</h3>
            <div class="well col-md-5">
                <input id="demo7" type="text" class="col-md-12 form-control" placeholder="Search cities..." autocomplete="off" />
            </div>
            <div class="col-md-7">
                <pre class="prettyprint">

    $('#demo7').typeahead({
         source: ['Toronto',
                 'Toronto1',
		 'Toronto2',
                 'Toronto3',
		 'Toronto4',
                 'Toronto5',
                 'Toronto6',
                 'Toronto7',
                 'Toronto8',
                 'Toronto9',
                 'Toronto10',
                 'Montreal',
                 'New York', 
                 'Buffalo', 
                 'Boston', 
                 'Columbus', 
                 'Dallas', 
                 'Vancouver', 
                 'Seattle', 
                 'Los Angeles'],
                 scrollBar:true
    });
                </pre>
            </div>
        </div>

    </div>
</div>

<script>
$(function() {
    function displayResult(item) {
        $('.alert').show().html('You selected <strong>' + item.value + '</strong>: <strong>' + item.text + '</strong>');
    }
    $('#demo1').typeahead({
        source: [
            {id: 1, name: 'Toronto'},
            {id: 2, name: 'Montreal'},
            {id: 3, name: 'New York'},
            {id: 4, name: 'Buffalo'},
            {id: 5, name: 'Boston'},
            {id: 6, name: 'Columbus'},
            {id: 7, name: 'Dallas'},
            {id: 8, name: 'Vancouver'},
            {id: 9, name: 'Seattle'},
            {id: 10, name: 'Los Angeles'}
        ],
        onSelect: displayResult
    });

    $('#demo2').typeahead({
        source: [
            {ID: 1, Name: 'Toronto'},
            {ID: 2, Name: 'Montreal'},
            {ID: 3, Name: 'New York'},
            {ID: 4, Name: 'Buffalo'},
            {ID: 5, Name: 'Boston'},
            {ID: 6, Name: 'Columbus'},
            {ID: 7, Name: 'Dallas'},
            {ID: 8, Name: 'Vancouver'},
            {ID: 9, Name: 'Seattle'},
            {ID: 10, Name: 'Los Angeles'}
        ],
        displayField: 'Name',
        valueField: 'ID',
        onSelect: displayResult
    });

    $('#demo3').typeahead({
        source: [
            {id: 1, full_name: 'Toronto', first_two_letters: 'To'},
            {id: 2, full_name: 'Montreal', first_two_letters: 'Mo'},
            {id: 3, full_name: 'New York', first_two_letters: 'Ne'},
            {id: 4, full_name: 'Buffalo', first_two_letters: 'Bu'},
            {id: 5, full_name: 'Boston', first_two_letters: 'Bo'},
            {id: 6, full_name: 'Columbus', first_two_letters: 'Co'},
            {id: 7, full_name: 'Dallas', first_two_letters: 'Da'},
            {id: 8, full_name: 'Vancouver', first_two_letters: 'Va'},
            {id: 9, full_name: 'Seattle', first_two_letters: 'Se'},
            {id: 10, full_name: 'Los Angeles', first_two_letters: 'Lo'}
        ],
        displayField: 'full_name',
        onSelect: displayResult
    });

    // Mock an AJAX request
//    $.mockjax({
//        url: '{{url('getAllSymbol')}}',
//        response: function($data ) {
//            
//            
//        $.get("{{url('getAllSymbol')}}", function(data) {
//           this.responseText = data;
//        }, 'json');



//            this.responseText = [
//                {id: 1, name: 'Toronto'},
//                {id: 2, name: 'Montreal'},
//                {id: 3, name: 'New York'},
//                {id: 4, name: 'Buffalo'},
//                {id: 5, name: 'Boston'},
//                {id: 6, name: 'Columbus'},
//                {id: 7, name: 'Dallas'},
//                {id: 8, name: 'Vancouver'},
//                {id: 9, name: 'Seattle'},
//                {id: 10, name: 'Los Angeles'}
//            ];
//        }
//    });

//    $('#demo4').typeahead({
//        ajax: '{{url('getAllSymbol')}}',
//        onSelect: displayResult
//    });
    
    
$.get("{{url('getAllSymbol')}}", function(data) {
    $("#demo4").typeahead({source: data});
}, 'json');


    $('#demo5').typeahead({
        ajax: {
            url: '{{url('getAllSymbol')}}',
            method: 'post',
            triggerLength: 1
        },
        onSelect: displayResult
    });
    $('#demo6').typeahead({
        source: [
            'Toronto',
            'Montreal',
            'New York',
            'Buffalo',
            'Boston',
            'Columbus',
            'Dallas',
            'Vancouver',
            'Seattle',
            'Los Angeles'],
        onSelect: displayResult
    });
    $('#demo7').typeahead({
        source: [
            'Toronto',
            'Toronto1',
            'Toronto2',
            'Toronto3',
            'Toronto4',
            'Toronto5',
            'Toronto6',
            'Toronto7',
            'Toronto8',
            'Toronto9',
            'Toronto10',
            'Montreal',
            'New York',
            'Buffalo',
            'Boston',
            'Columbus',
            'Dallas',
            'Vancouver',
            'Seattle',
            'Los Angeles'],
        scrollBar: true,
        onSelect: displayResult
    });
});
</script>

@stop

