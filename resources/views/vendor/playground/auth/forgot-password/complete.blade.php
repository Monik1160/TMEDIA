@extends("playground::theme.layouts.login.login-2")

@section("title",trans("playground::auth.forgot_password_sent_title"))

@section('content')
    {!! Form::open(['route' => 'playground.auth.password.reset', 'method'=>'post', 'id' => 'resetForm', 'class' => '', 'autocomplete' => 'off' ]) !!}

    {!! Form::hidden('token', $token) !!}

    <div class="form-group {{ $errors->has('email') ? "has-danger" : "" }}">
        {!! Form::text('email',  $email ?? old('email'),['class' => 'form-control', 'placeholder' => trans("playground::auth.email"), "data-rule-required='true'"]) !!}
        @if($errors->has('email'))
            <span id="validation_username-error"
                  class="help-block help-block-error">{{ $errors->first('email') }}</span>
        @endif
    </div>

    <div class="form-group {{ $errors->has('password') ? "has-danger" : "" }}">
        {!! Form::password('password', ['id' => 'password','class' => 'form-control', 'placeholder' => trans("playground::auth.new_password"), "data-rule-required='true'"]) !!}
        @if($errors->has('password'))
            <span id="validation_username-error"
                  class="help-block help-block-error">{{ $errors->first('password', ':message') }}</span>
        @endif
    </div>

    <div class="form-group {{ $errors->has('password_confirmation') ? "has-danger" : "" }}">
        {!! Form::password('password_confirmation', ['class' => 'form-control', 'placeholder' => trans("playground::auth.new_password_confirm"), "data-rule-required='true'"]) !!}
        @if($errors->has('password_confirmation'))
            <span id="validation_username-error"
                  class="help-block help-block-error">{{ $errors->first('password_confirmation', ':message') }}</span>
        @endif
    </div>

    <button id="btn-trigger" class="btn btn-theme btn-full"
            data-loading="<i class='icon-fa icon-fa-spinner icon-fa-spin'></i>  {{trans('playground::common/actions.changing')}}...">
        {{trans("playground::auth.forgot_password_sent_request")}}
    </button>

    {{ Form::close() }}
@stop

@section('scripts')
    <script>
        var passwordForm = function () {

            var handleValidation = function () {

                // for more info visit the official plugin documentation:
                // http://docs.jquery.com/Plugins/Validation
                var form = $('#resetForm');

                form.validate({
                    errorElement: 'span', //default input error message container
                    errorClass: 'help-block help-block-error', // default input error message class
                    focusInvalid: false, // do not focus the last invalid input
                    ignore: "",  // validate all fields including form hidden input
                    rules: {
                        email: {
                            required: true,
                            email: true
                        },
                        password: {
                            minlength: 6,
                            required: true
                        },
                        password_confirmation: {
                            equalTo: "#password"
                        }
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
            passwordForm.init();
        });
    </script>
@stop

