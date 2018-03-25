@extends('layouts.app')

@section('main-content')
    <div class="row">
        <h1>Add Prize</h1>
    </div>

    <div class="row">
        <div id="edit-license-container" class="col-md-7">
            <div class="row">
                {!! Form::open(['id' => 'edit-prize-form', 'method' => 'put']) !!}
                @include('Partials.Forms._prize')
                {!! Form::close() !!}
            </div>
            <div class="row margin-top25">
                <div class="col-md-12 text-right">
                    <a href="{{ url('/prizes/list') }}" class="btn btn-link">Cancel</a>
                    <button type="button" id="toggle-prize-update" class="btn btn-primary">Update</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom-js')
<script type="text/javascript">
    $(document).ready(function(){
        $('.summernote').summernote();
    });
</script>
@endsection
