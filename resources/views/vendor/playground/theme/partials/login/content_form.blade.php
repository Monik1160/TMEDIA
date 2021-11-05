<div id="app" class="@yield('app-wrapper')">
    <div class="login-box">

        <div class="box-wrapper">
            <div class="logo-main">
                <a href="javascript:void(0);">
                    <img src="{{ asset('packages/playground/images/logo.svg') }}"
                         alt="{{ config('backpack.base.project_name')}} Logo">
                </a>
            </div>

            @include('vendor.playground.theme.partials.playground-notifications')
            @yield('content')

            <div class="page-copyright">
                <p>{{config('backpack.base.project_name')}} © {{date('Y')}}</p>
            </div>

        </div>
    </div>
</div>
