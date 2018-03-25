@extends('layouts.registration')

@section('main-content')
<div class="row">
    <div class="col-md-12">
        <div class="text-center">
            <h2>{{ ucwords($raffle_info->name) }}</h2>
            <h5>{{ $raffle_info->subtitle }}</h5>
        </div>
    </div>
</div>

<div class="row">
    {{-- left panel --}}
    <div class="col-xs-5 col-md-5" style="margin-left: 10%">
        <div class="login-box">
            <div class="row">
                <div class="col-md-12 text-center">
                    @if (is_null($prize->image))
                    <img src="{{ url('/img/default.jpeg') }}" alt="default" class="prize-image">
                    @else
                    <img src="{{ url('/uploads/'.$prize->image) }}" alt="{{ $prize->name }}" class="prize-image">
                    @endif
                </div>
            </div>

            @if (!empty($raffle_info->description))
            <div class="row margin-top10">
                <div class="col-md-12 text-center">
                    <p>{!! html_entity_decode($raffle_info->description) !!}</p>
                </div>
            </div>
            @endif

            @if (!empty($raffle_info->mechanics))
            <div class="row">
                <label><strong>Mechanics:</strong></label>
                <p>{!! html_entity_decode($raffle_info->mechanics) !!}</p>
            </div>
            @endif
        </div>
    </div>

    {{-- rigth panel --}}
    <div class="col-xs-5 col-md-5">
        <div class="login-box">
            @if (count($errors->all()))
                <div class="alert alert-danger" role="alert">
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('status'))
                <div class="alert alert-warning">
                    {{ session('status') }}
                </div>
            @endif

            <div id="registration-container" class="login-box-body">
                <div class="registration-wrapper">
                    <p class="login-box-msg"> Sign Up With Email </p>

                    <form id="registration-form" action="{{ $form_action }}" method="post">
                        <div class="form-group">
                            <!-- <label for="email">Email address</label> -->
                            <input type="email" class="form-control" id="email" name="email" placeholder="Email Address">
                        </div>

                        <div class="row margin-bottom20">
                            <div class="col-md-12 padding-left25">
                                <div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_KEY') }}"></div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <button type="button" id="submit-registration" class="btn btn-primary btn-block btn-flat">Register</button>
                            </div>
                        </div>

                        {!! Form::hidden('_token', csrf_token()) !!}
                        {!! Form::hidden('raffle_id', $raffle_info->raffle_id) !!}
                        {!! Form::hidden('raffle_action_id', $default_raffle_action[0]['raffle_action_id'], ['id' => 'raffle-action-id']) !!}
                    {!! Form::close() !!}

                    <div class="text-center margin-top10">
                        @if ($days_remaining > 1)
                            <p>Days Remaining: {{ $days_remaining }} days</p>
                        @else
                            <p>Days Remaining: {{ $days_remaining }} day</p>
                        @endif
                    </div>
                </div>
            </div>

            <div id="social-media-container" class="row text-center hidden">
                <div class="bg-success padding-20 text-center">
                    <h4> <strong>You have successfully registered.</strong> </h4>
                </div>

                <div class="row margin-top20 text-center">
                    <div id="facebook-share-wrapper" class="hidden">
                        <p class="small">Share this raffle to facebook and get one (1) additional raffle entry.</p>
                        <span id="facebook-share" class="facebook-share"></span>
                    </div>

                    <form id="signup-form">
                        <input type="hidden" id="code" name="code" />
                        <input type="hidden" id="facebook-share-id" name="facebook_share_id" />
                        <input type="hidden" id="raffle" name="raffle" value="{{ $raffle_info->raffle_id }}" />
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('custom-css')
<style type="text/css">
    #registration-container {
        min-height: 298px;
    }

    .facebook-share {
        background: url('/img/facebook_share_button.png') 1072px, 1500px;
        display: inline-block;
        height: 210px;
        width: 220px;
        overflow: hidden;
        zoom: 0.2;
        -moz-transform:scale(0.2);
        -moz-transform-origin: 110px 0;
        cursor: pointer;
    }
</style>
@endsection

@section('custom-js')
<script type="text/javascript">
    $(document).ready(function () {
        $('#registration-form').validate({
            rules: {
                email: {
                    required: true,
                    email: true
                }
            },
            submitHandler: function (form) {
                $.ajax({
                    url: '{{ $form_action }}',
                    type: 'post',
                    data: $(form).serialize(),
                    dataType: 'json',
                    beforeSend: function () {
                        $('.registration-wrapper').addClass('hidden');
                        $('#registration-container').html("<div class='text-center' style='margin-top: 30%'><i class='fa fa-cog fa-2x fa-spin' aria-hidden='true'></i><br><p>Submitting...</p></div>");
                    },
                    success: function (response) {
                        if (response.success) {
                            $('#registration-container').addClass('hidden');
                            $('#social-media-container').removeClass('hidden');

                            $('#code').val(response.data.code);
                            $.each(response.data.actions, function (arr_index, arr_value) {
                                if (arr_value.name == 'Share on Facebook') {
                                    $('#facebook-share-wrapper').removeClass('hidden');
                                    $('#facebook-share-id').val(arr_value.raffle_action_id);
                                }
                            });
                        }
                    }
                });
            }
        });

        $(document).on('click', '#submit-registration', function (e) {
            e.preventDefault();
            $('#registration-form').submit();
        });


        $(document).on('click', '#facebook-share', function (e) {
            $.ajaxSetup({ cache: true });

            FB.getLoginStatus(function(response) {
                if (response.status == 'connected') {
                    share_raffle();
                } else {
                    FB.login(function(response) {
                        if (response.authResponse){
                            share_raffle();
                        } else {
                            bootbox.alert({
                                message: "<h4>Auth Cancelled</h4>"
                            });
                        }
                    }, { scope: 'email' });
                }
            });

            function share_raffle()
            {
                FB.ui({
                    method: 'share',
                    title: '{{ $raffle_info->name }}',
                    href: '{{ $raffle_url }}',
                },

                function(response) {
                    if (response && !response.error_code) {
                        FB.api('/me?fields=name,email', function (userInfo) {
                            $.ajax({
                                url: '/raffle/signup',
                                type: 'post',
                                data: $('#signup-form').serialize(),
                                dataType: 'json',
                                success: function (response) {
                                    bootbox.alert({
                                        message: "<h4>"+ response.message +"</h4>"
                                    });
                                }
                            });
                        });
                    }
                });
            }

        });
    });
</script>
@endsection
