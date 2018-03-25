@extends('layouts.app')

@section('main-content')

<div class="login-box">
    @if(Session::has('errors'))
    <div class="flash-message">
        <p class="alert alert-danger"><strong>Oh snap!</strong> {{ Session::get('errors') }}</p>
    </div>
    @endif

    <div class="login-box-body">
        <p class="login-box-msg"> Sign in to start your session </p>

        <form action="{{ url('/login') }}" method="post">
            <div class="form-group">
                <label for="email">Email address</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Email">
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Password">
            </div>

            <div class="row">
                <div class="col-md-4"> </div>
                <div class="col-md-4"> </div>

                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
                </div>
            </div>

            <input type="hidden" name="_token" value="{{ csrf_token() }}">
        {!! Form::close() !!}
    </div>
</div>

@endsection
