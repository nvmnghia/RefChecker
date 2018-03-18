@extends('layouts.app')

@section('page-style')
<link href="{{ asset('css/datatables.min.css') }}" rel="stylesheet">
<style type="text/css">
	.wrap-looong-text {
	  /* These are technically the same, but use both */
	  overflow-wrap: break-word;
	  word-wrap: break-word;

	  -ms-word-break: break-all;
	  /* This is the dangerous one in WebKit, as it breaks things wherever */
	  word-break: break-all;
	  /* Instead use this non-standard one: */
	  word-break: break-word;

	  /* Adds a hyphen where the word breaks, if supported (No Blink) */
	  -ms-hyphens: auto;
	  -moz-hyphens: auto;
	  -webkit-hyphens: auto;
	  hyphens: auto;
	}
</style>
@endsection

@section('page-script')
<script type="text/javascript" src="{{ asset('js/popper.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/bootstrap.min.js') }}"></script>

<script type="text/javascript" src="{{ asset('js/datatables.min.js') }}"></script>
<script type="text/javascript">
	var data;
	var currentStyle = 'Havard';

    function getResult() {
        $.ajax({
            url: '/get_result',
            type: 'GET',
            success: function(json) {
            	
                if (! $.trim(json)){
                    // Blank, so request for the result again in 5 sec
                    setTimeout(getResult, 5000);                    
                } else {
                	$('#loading').hide();
        			$('#result').show();

        			data = JSON.parse(json);

        			$('#compare').DataTable({
        				'data': getReferences(data, 'Havard'),
        				'ordering': false,
        				'pageLength': 5,
        				'columns' : [
				            { "data" : "original" },
				            { "data" : "corrected" },
				        ],
				        'columnDefs': [
				        	{
				        		'createdCell': detectErr,
				        		'targets': 1
				        	}
				        ]
        			});
                }
            },
            error: function() {
            	// Again...
            	setTimeout(getResult, 5000);
            }
        })
    }

    function detectErr(td, cellData, rowData, row, col) {
    	var original = data['original'][row].toLowerCase();
    	var corrected = data[currentStyle][row];

    	var wordsCorrected = corrected.split(/\s+/);
    	console.log(wordsCorrected);

    	var newCell = '<span>';
    	for (var i = 0; i < wordsCorrected.length; ++i) {
    		if (original.indexOf(wordsCorrected[i].toLowerCase()) === -1) {
    			// Original reference doesn't have this word, hightlight!
    			newCell += '<mark class="text-danger">' + wordsCorrected[i] + '</mark>' + ' ';
    		} else {
    			newCell += wordsCorrected[i] + ' ';
    		}
    	}
    	newCell += '<span>';
    	console.log(newCell);

    	$(td).empty().append(newCell);
    }

    function getReferences(json, style) {
    	var original = json.original;
    	var corrected;
    	var list = new Array(original.length);
    	
    	switch(style) {
    		case 'APA':
    			corrected = json.APA;
    			break;

    		case 'BibTeX':
    			corrected = json.BIBTEX;
    			break;

    		default:
    			// Havard
    			corrected = json.Havard;
    	}

    	for (var i = 0; i < original.length; ++i) {
    		var elem = {};
    		elem.original = original[i];
    		elem.corrected = corrected[i];

    		list[i] = elem;
    	}

    	console.log(list);

    	return list;
    }

    $(document).ready(function() {
    	getResult();

    	$('#style > button.btn').click(function() {
    		var selectedStyle = $(this).text();
    		if (selectedStyle != currentStyle) {
    			$('#' + currentStyle).addClass('btn-outline-primary').removeClass('btn-primary');
    			$('#' + selectedStyle).addClass('btn-primary').removeClass('btn-outline-primary');

    			currentStyle = selectedStyle;
    		}
    	})

        $(".dropdown-menu li a").click(function(){
	        $(".btn:first-child").text($(this).text());
	        $(".btn:first-child").val($(this).text());
	   });
    });
</script>
@endsection

@section('content')
<div class="container">
    <br>

    <div class="row" id="loading">
    	<img class="mx-auto d-block" src="loading.gif">
    </div>

    <div class="row">
    	<div class="col-md-6">
    		<h2>Result</h2>
    	</div>

    	<br>

    	<div class="col-md-6">
    		<span class="float-right">
	    		<div class="btn-group" id="style">
					<button type="button" id="Havard" class="btn btn-primary">Havard</button>
					<button type="button" id="APA" class="btn btn-outline-primary">APA</button>
					<button type="button" id="BibTeX" class="btn btn-outline-primary">BibTeX</button>
				</div>
				<button type="button" class="btn btn-outline-primary" id="copy">Copy</button>
    		</span>
    	</div>
    </div>

    <br>

    <div class="row" id="result">
    	<table class="table" id="compare">
    		<thead>
    			<tr>
    				<th width="50%">
    					<h4>Original references</h4>
    				</th>
    				<th width="50%">
    					<h4>Corrected references</h4>
    				</th>
    			</tr>
    		</thead>
    		<tbody>
    			
    		</tbody>
    	</table>

   	</div>

	<br>
</div>
@endsection