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
	var currentStyle = 'APA';

    function getResult() {
        // Check whether DataTable is initialized or not
        if ($.fn.DataTable.isDataTable('#compare')) {
            // Already initialized, now change data
            var datatable = new $.fn.dataTable.Api('#compare');
            
            datatable.clear();
            datatable.rows.add(getReferences(data, currentStyle));
            datatable.draw();

        } else {
            // Haven't initialized, no data loaded, do it
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
                        'data': getReferences(data, currentStyle),
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
    }

    function detectErr(td, cellData, rowData, row, col) {
    	var original = data['original'][row].toLowerCase();
    	var corrected = data[currentStyle.toLowerCase()][row];

        if (corrected !== 'Not Found') {
        	var wordsCorrected = corrected.split(/\s+/);
        	var newCell = '<span>';
            
        	for (var i = 0; i < wordsCorrected.length; ++i) {
        		if (original.indexOf(wordsCorrected[i].toLowerCase()) === -1) {
        			// Original reference doesn't have this word, hightlight!
        			newCell += '<mark class="text-danger"><strong>' + wordsCorrected[i] + '</strong></mark>' + ' ';
        		} else {
        			newCell += wordsCorrected[i] + ' ';
        		}
        	}
        	newCell += '<span>';

        	$(td).empty().append(newCell);
        }

    }

    function getReferences(json, style) {
        console.log(json);

    	var original = json.original;
    	var corrected;
    	var list = new Array(original.length);
    	
    	// switch(style) {
    	// 	case 'BibTeX':
    	// 		corrected = json.BibTeX;
    	// 		break;

     //        case 'IEEE':
     //            corrected = json.IEEE

     //        case 'ACM':

     //        case 'Chicago':

     //        case 'CSE':

     //        case 'MLA':

    	// 	default:
    	// 		// APA
    	// 		corrected = json.APA;
    	// }

        corrected = json[style.toLowerCase()];

    	for (var i = 0; i < original.length; ++i) {
    		var elem = {};
    		elem.original = original[i];
    		elem.corrected = corrected[i];

    		list[i] = elem;
    	}

    	return list;
    }

    function changeStyle(e) {
        var selectedStyle = $(this).html().trim();

        if (selectedStyle !== currentStyle) {

            // First change the dropdown label
            $('#button-style').html(selectedStyle);

            // Update var
            currentStyle = selectedStyle;

            // Update data in the table
            getResult();
        }

        // Almost forgot
        e.preventDefault();
    }

    $(document).ready(function() {
    	getResult();

    	// $('#style > button.btn').click(function() {
    	// 	var selectedStyle = $(this).text();
    	// 	if (selectedStyle != currentStyle) {
    	// 		$('#' + currentStyle).addClass('btn-outline-primary').removeClass('btn-primary');
    	// 		$('#' + selectedStyle).addClass('btn-primary').removeClass('btn-outline-primary');

    	// 		currentStyle = selectedStyle;
    	// 	}
    	// })

    //     $(".dropdown-menu li a").click(function(){
	   //      $(".btn:first-child").text($(this).text());
	   //      $(".btn:first-child").val($(this).text());
	   // });

       $('#dropdown-style > a').click(changeStyle);
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
                <div class="dropdown">
                    <button id="button-style" class="btn btn-outline-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        APA
                    </button>
                    <div id="dropdown-style" class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" href="#" id="APA">APA</a>
                        <a class="dropdown-item" href="#" id="MLA">MLA</a>
                        <a class="dropdown-item" href="#" id="IEEE">IEEE</a>
                        <a class="dropdown-item" href="#" id="ACM">ACM</a>
                        <a class="dropdown-item" href="#" id="Chicago">Chicago</a>
                        <a class="dropdown-item" href="#" id="CSE">CSE</a>
                        <a class="dropdown-item" href="#" id="BibTeX">BibTeX</a>
                    </div>

				    <button type="button" class="btn btn-outline-primary" id="copy">Copy</button>
                </div>
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