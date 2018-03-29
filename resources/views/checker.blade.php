@extends('layouts.app')

@section('page-style')
<link href="{{ asset('css/fileinput.css') }}" rel="stylesheet">
@endsection

@section('page-script')
<script type="text/javascript" src="{{ asset('js/fileinput.min.js') }}"></script>
<script type="text/javascript">
    var currentInput = "file-input";

    $(document).ready(function() {
        $('#docUp').fileinput({
        	showUpload: false,
        	allowedFileExtensions: ['pdf'],
        	required: true
        });

        $('#input-type > button.btn').click(function() {
            var selectedInput = $(this).attr('id');
            if (selectedInput !== currentInput) {
                // Update button group
                $('#' + currentInput).addClass('btn-outline-primary').removeClass('btn-primary');
                $('#' + selectedInput).addClass('btn-primary').removeClass('btn-outline-primary');

                // Update view
                $('#div-' + selectedInput).show();
                $('#div-' + currentInput).hide();
                
                // Update variable
                currentInput = selectedInput;
            }
        });
    });
</script>
@endsection

@section('content')
<div class="container">
    <div class="row">
        <legend>Upload your scientific document</legend>
    </div>

    <div class="wrapper text-center" width=100%>
        <div id="input-type" class="btn-group">
            <button class="btn btn-primary" id="file-input">File input</button>
            <button class="btn btn-outline-primary" id="text-input">Text input</button>
        </div>
    </div>

    <div id="div-file-input">
        <form id="file-upload-form" action="{{ url('fileUpload') }}" enctype="multipart/form-data" method="POST" accept=".pdf">
            {{ csrf_field() }} <!-- Mandatory for Laravel -->
            <div class="form-group">
                <label>Upload the file to check</label>
                <input type="file" name="doc" id="docUp">
            </div>

            <button type="submit" class="btn btn-primary float-right">Submit</button>
        </form>
    </div>

    <br>

    <div id="div-text-input" style="display: none;">
        <form id="text-upload-form" action="{{ url('textUpload') }}" enctype="multipart/form-data" method="POST">
            {{ csrf_field() }} <!-- Mandatory for Laravel -->
            <div class="form-group">
                <label class="float-left">Paste the references to check</label>
                <h6 class="float-right font-weight-bold">Note: each reference should have one trailing new line</h6>

                <textarea class="form-control" rows="10" name="list-references" form="text-upload-form"></textarea>
            </div>
            
            <button type="submit" class="btn btn-primary float-right">Submit</button>
        </form>
    </div>
</div>
@endsection