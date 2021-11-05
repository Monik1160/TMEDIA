@extends("vendor.playground.theme.layouts.login.login-3")

@section("title",trans('auth.register'))

@section('content')
    <form class="col-md-12 p-t-10" role="form" method="POST" action="{{ route('backpack.auth.register') }}">
        {!! csrf_field() !!}
        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
            <div>

                <input type="text" class="form-control" name="name" value="{{ old('name') }}"
                       placeholder="Team Name">

                @if ($errors->has('name'))
                    <span class="help-block-error">
                                     {{$errors->first('name')}}
                                    </span>
                @endif
            </div>
        </div>
        <div class="form-group{{ $errors->has('first_name') ? ' has-error' : '' }}">
            <div>
                <input type="text" class="form-control" name="first_name" value="{{ old('first_name') }}"
                       placeholder="First name">

                @if ($errors->has('first_name'))
                    <span class="help-block-error">
                                        {{ $errors->first('first_name') }}
                                    </span>
                @endif
            </div>
        </div>
        <div class="form-group{{ $errors->has('last_name') ? ' has-error' : '' }}">
            <div>
                <input type="text" class="form-control" name="last_name" value="{{ old('last_name') }}"
                       placeholder="Last name">

                @if ($errors->has('last_name'))
                    <span class="help-block-error">
                                        {{ $errors->first('last_name') }}
                                    </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has(backpack_authentication_column()) ? ' has-error' : '' }}">
            <div>
                <input type="{{ backpack_authentication_column()=='email'?'email':'text'}}" class="form-control"
                       name="{{ backpack_authentication_column() }}"
                       value="{{ old(backpack_authentication_column()) }}" placeholder="Email">

                @if ($errors->has(backpack_authentication_column()))
                    <span class="help-block-error">
                                        {{ $errors->first(backpack_authentication_column()) }}
                                    </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
            <div>
                <input type="password" class="form-control" name="password" placeholder="Password">

                @if ($errors->has('password'))
                    <span class="help-block-error">
                                        {{ $errors->first('password') }}
                                    </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
            <div>
                <input type="password" class="form-control" name="password_confirmation" placeholder="Confirm Password">

                @if ($errors->has('password_confirmation'))
                    <span class="help-block-error">
                                        {{ $errors->first('password_confirmation') }}
                                    </span>
                @endif
            </div>
        </div>

        <div class="form-group">
            <div>
                <button type="submit" id="btn-trigger" class="btn btn-theme btn-full" style="cursor: pointer;">
                    {{ trans('backpack::base.register') }}
                </button>
            </div>
        </div>
    </form>
@endsection


