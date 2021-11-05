@extends("vendor.playground.theme.layouts.login.login-3")

@section("title",trans('auth.sign_in_page_title'))

@section('content')
    {!! Form::open(['route' => 'backpack.auth.login', 'method'=>'post', 'id' => 'loginForm', 'class' => '', 'autocomplete' => 'off' ]) !!}

    <div class="form-group {{ $errors->has($login_field) ? "has-danger" : "" }}">
        {!! Form::text($login_field, null,['class' => 'form-control', 'placeholder' => trans("auth.". $login_field), "data-rule-required='true'"]) !!}
        @if($errors->has($login_field))
            <span id="validation_username-error"
                  class="help-block help-block-error">{{ $errors->first($login_field) }}</span>
        @endif
    </div>

    <div class="form-group {{ $errors->has('password') ? "has-danger" : "" }}">
        <div class='controls with-icon-over-input'>
            {!!  Form::password('password', ['class' => 'form-control', 'placeholder' => trans("auth.password"), "data-rule-required" => "true"]) !!}
            @if($errors->has('password'))
                <span id="validation_password-error"
                      class="help-block help-block-error">{{ $errors->first('password') }}</span>
            @endif
        </div>
    </div>

    <div class="other-actions row">
        <div class="col-6">
            <div class="checkbox">
                <label class="c-input c-checkbox">
                    {{ Form::checkbox('remember') }}
                    <span class="c-indicator"></span>
                    {!!  trans("auth.remember") !!}
                </label>
            </div>
        </div>
        <div class="col-6 text-right">
            <a href="{{route('backpack.auth.password.reset')}}" class="auth-action-links">{!! __("auth.forgot_password_link") !!}</a>
        </div>
    </div>

    <button id="btn-trigger" class="btn btn-theme btn-full"
            data-loading="<i class='icon-fa icon-fa-spinner icon-fa-spin'></i>  Logging in...">{!!  trans("auth.sign_in_button") !!}</button>

    {{ Form::close() }}
@stop

@section('scripts')
    <script>
        var LoginForm = function () {

            // Login form validation
            var handleValidation = function () {

                var form = $('#loginForm');

                form.validate({
                    errorElement: 'span', //default input error message container
                    errorClass: 'help-block help-block-error', // default input error message class
                    focusInvalid: false, // do not focus the last invalid input
                    ignore: "",  // validate all fields including form hidden input
                    rules: {
                        "password": {
                            minlength: 2,
                            required: true
                        },
                        "{{$login_field}}": {
                            required: true,
                            email: Boolean("{{  ( $login_field == 'email') ? true: false  }}")
                        },
                    },

                    highlight: function (element) { // hightlight error inputs
                        $(element)
                            .closest('.form-group').addClass('has-danger'); // set danger class to the control group
                    },

                    unhighlight: function (element) { // revert the change done by hightlight
                        $(element)
                            .closest('.form-group').removeClass('has-danger'); // set danger class to the control group
                    },

                    success: function (label) {
                        label
                            .closest('.form-group').removeClass('has-danger'); // set success class to the control group
                    },
                    submitHandler: function (form) {
                        $("#btn-trigger").html($("#btn-trigger").data('loading'));
                        form.submit();
                    }
                });

            };

            return {
                //main function to initiate the module
                init: function () {
                    handleValidation();
                }
            };

        }();

        jQuery(document).ready(function () {
            LoginForm.init();
        });
    </script>
@stop
