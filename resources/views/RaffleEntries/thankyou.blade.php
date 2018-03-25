@extends('layouts.registration')

@section('main-content')
<div class="login-box">
    <div class="bg-success padding-20 text-center">
        <h4> <strong>You have successfully registered.</strong> </h4>
    </div>

    <div class="text-center margin-top10">
        <a href="{{ url()->previous() }}">Back</a>
    </div>
</div>
@endsection
