@extends("playground::vendor.playground.theme.layouts.login.login-2")

@section("title",trans("playground::auth.forgot_password"))

@section('content')
    {!! Form::open(['route' => 'playground.auth.password.email', 'method'=>'post', 'id' => 'resetForm', 'class' => '', 'autocomplete' => 'off' ]) !!}

    <div class="form-group {{ $errors->has('email') ? "has-danger" : "" }}">
        {!! Form::text('email', null,['class' => 'form-control', 'placeholder' => trans("playground::auth.email"), "data-rule-required='true'"]) !!}
        @if($errors->has('email'))
            <span id="validation_username-error"
                  class="help-block help-block-error">{{ $errors->first('email') }}</span>
        @endif
    </div>

    <button id="btn-trigger" class="btn btn-theme btn-full"
            data-loading="<i class='icon-fa icon-fa-spinner icon-fa-spin'></i>  {{trans('playground::common/actions.sending')}}...">
        {{trans("playground::auth.sent_reset_link")}}
    </button>

    <div class="other-actions">
        <p>{{trans('playground::auth.or_auth')}} <a href="{{route('playground.auth.login')}}"
                 class="auth-action-links">{{trans('playground::auth.sign_in_button')}}</a></p>
    </div>

    {{ Form::close() }}
@stop

@section('scripts')
    <script>
        var resetForm = function () {

            // Login form validation
            var handleValidation = function () {

                var form = $('#resetForm');

                form.validate({
                    errorElement: 'span', //default input error message container
                    errorClass: 'help-block help-block-error', // default input error message class
                    focusInvalid: false, // do not focus the last invalid input
                    ignore: "",  // validate all fields including form hidden input
                    rules: {
                        "email": {
                            required: true,
                            email: true
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
            resetForm.init();
        });
    </script>
@stop

