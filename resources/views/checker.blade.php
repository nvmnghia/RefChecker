@extends('layouts.app')

@section('page-style')
<link href="{{ asset('css/fileinput.css') }}" rel="stylesheet">
@endsection

@section('page-script')
<script type="text/javascript" src="{{ asset('js/fileinput.min.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#docUp').fileinput({
        	showUpload: false,
        	allowedFileExtensions: ['pdf'],
        	required: true
        });
    });
</script>
@endsection

@section('content')
<div class="container">
    <form action="{{ url('fileUpload') }}" enctype="multipart/form-data" method="POST" accept=".pdf">
    	<legend>Upload your scientific document</legend>
        {{ csrf_field() }} <!-- Mandatory for Laravel -->
        <div class="form-group">
            <label>Upload the file to check</label>
            <input type="file" name="doc" id="docUp">
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
@endsection