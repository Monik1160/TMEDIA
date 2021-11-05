@extends("vendor.playground.theme.layouts.login.login-3")

@section("title", isset($title) ? $title.' :: '.config('backpack.base.project_name') : config('backpack.base.project_name'))

@section("logo_image", asset('packages/playground/images/logo.svg'))

@section('content')
    <form action="{{ route('backpack.auth.login') }}" method="post" id="loginForm" autocomplete="off">
        {!! csrf_field() !!}

        <div class="form-group {{ $errors->has($username) ? "has-danger" : "" }}">
            <input
                type="text"
                class="form-control{{ $errors->has($username) ? ' is-invalid' : '' }}"
                name="{{ $username }}"
                value="{{ old($username) }}" id="{{ $username }}"
                placeholder="{{ config('backpack.base.authentication_column_name') }}"
                data-rule-required="true"
            >
            @if($errors->has($username))
                <span id="validation_username-error"
                      class="help-block help-block-error">{{ $errors->first($username) }}</span>
            @endif
        </div>

        <div class="form-group {{ $errors->has('password') ? "has-danger" : "" }}">
            <div class='controls with-icon-over-input'>
                <input
                    type="password"
                    class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                    placeholder="{{ trans('backpack::base.password') }}"
                    name="password"
                    id="password"
                    data-rule-required="true"
                >
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
                        <input type="checkbox" name="remember">
                        <span class="c-indicator"></span>
                        {{ trans('backpack::base.remember_me') }}
                    </label>
                </div>
            </div>
            <div class="col-6 text-right">
                @if (backpack_users_have_email())
                    <a
                        href="{{route('backpack.auth.password.reset')}}"
                        class="auth-action-links"
                    >
                        {{ trans('backpack::base.forgot_your_password') }}
                    </a>
                @endif
            </div>
        </div>

        <button
            id="btn-trigger"
            class="btn btn-theme btn-full"
            data-loading="<i class='icon-fa icon-fa-spinner icon-fa-spin'></i>  Logging in..."
        >
            {{ trans('backpack::base.login') }}
        </button>

        @if (config('backpack.base.registration_open'))
            <div class="second_cta_auth text-center">
                <a class="auth-action-links" href="{{ route('backpack.auth.register') }}">Registrarse</a>
            </div>
        @endif

    </form>
@endsection

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
                        "{{$username}}": {
                            required: true,
                            email: Boolean("{{  ( $username == 'email') ? true: false  }}")
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
@endsection
