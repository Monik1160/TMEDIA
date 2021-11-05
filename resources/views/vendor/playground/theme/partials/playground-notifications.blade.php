{{--@if (session('status'))--}}
{{--    <div class="alert alert-success" role="alert">--}}
{{--        {{ session('status') }}--}}
{{--    </div>--}}
{{--@endif--}}

{{--<div id="flash-msg">--}}
{{--    @include('flash::message')--}}
{{--</div>--}}

{{--@foreach (Alert::get() as $alert)--}}
{{--@switch($alert->area)--}}

{{--@case('notie')--}}
{{--<div class="laraspace-notify hidden-xs-up" data-driver="{{ $alert->area }}" data-notify="{{ $alert->type }}"--}}
{{--data-message="{{ $alert->message }}"></div>--}}
{{--@break--}}

{{--@case('toastr')--}}
{{--<div class="laraspace-notify hidden-xs-up" data-driver="{{ $alert->area }}"--}}
{{--data-notify="{{ $alert->type }}"--}}
{{--data-message="{{ $alert->message }}"></div>--}}
{{--@break--}}

{{--@case('block')--}}
{{--<div class="alert alert-{{ $alert->class }} alert-dismissable">--}}
{{--{{ $alert->message }}--}}
{{--</div>--}}
{{--@break--}}

{{--@default--}}
{{--<div class="alert alert-{{ $alert->class }} alert-dismissable">--}}
{{--<a class="close" data-dismiss="alert" href="#">×</a>--}}
{{--<i class="fa fa-times"></i>--}}
{{--{{ $alert->message }}--}}
{{--</div>--}}
{{--@endswitch--}}
{{--@endforeach--}}


