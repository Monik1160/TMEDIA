<!-- This file is used to store sidebar items, starting with Backpack\Base 0.9.0 -->
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i
            class="nav-icon fal fa-tachometer-alt-fastest"></i> {{ trans('backpack::base.dashboard') }}</a></li>


<!--Autobuses -->
@can('logistics')
    <li class="nav-item nav-dropdown">
        <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon fal fa-bus"></i> Buses Manager</a>
        <ul class="nav-dropdown-items">
            <li class='nav-item'><a class='nav-link' href='{{ backpack_url('autobusero') }}'><i
                        class="nav-icon fas fa-business-time"></i> Autobuseros</a></li>
            <li class='nav-item'><a class='nav-link' href='{{ backpack_url('bus') }}'><i
                        class='nav-icon fa fas fa-bus'></i> Buses</a></li>
            <li class='nav-item'><a class='nav-link' href='{{ backpack_url('carroceria') }}'><i
                        class='nav-icon fas fa-list-ol'></i> Carrocerías</a></li>
            <li class='nav-item'><a class='nav-link' href='{{ backpack_url('zonasbuses') }}'><i
                        class='nav-icon fas fa-bus'></i>
                    Zonas Publicitarias Buses</a></li>
        </ul>
    </li>
@endcan
<!--Autobuses -->
@can('logistics')
    <li class="nav-item nav-dropdown">
        <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon far fa-toolbox"></i> Tools</a>
        <ul class="nav-dropdown-items">
            <li class='nav-item'><a class='nav-link' href='{{ backpack_url('autobuseros_rutas') }}'>
                    <i class="nav-icon fas fa-route"></i> Autobuseros Rutas</a></li>
            <li class='nav-item'><a class='nav-link' href='{{ backpack_url('busesrutas') }}'>
                    <i class="nav-icon fas fa-bus"></i> Autobuseros Rutas Buses</a></li>
        </ul>
    </li>
@endcan
<!--Ads Manager -->
<li class="nav-item nav-dropdown">
    <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon fas fa-megaphone"></i> Ads Manager</a>
    <ul class="nav-dropdown-items">
        <li class='nav-item'><a class='nav-link' href='{{ backpack_url('campañas') }}'><i
                    class="nav-icon far fa-alarm-clock"></i> Campañas</a></li>
        @can('adsmanager')
            <li class='nav-item'><a class='nav-link' href='{{ backpack_url('zonapublicitaria') }}'><i
                        class='nav-icon fa fas fa-bus'></i> Zonas Publicitarias Publimedia</a></li>
        @endif
    </ul>
</li>
@can('contabilidad')
    <!--Contablidad -->
    <li class="nav-item nav-dropdown">
        <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon fas fa-calculator-alt"></i> Contabilidad</a>
        <ul class="nav-dropdown-items">
            <li class='nav-item'><a class='nav-link' href='{{ backpack_url('clientes') }}'><i
                        class='nav-icon far fa-address-book'></i> Clientes</a></li>
{{--            <li class='nav-item'><a class='nav-link' href='{{ backpack_url('contact') }}'><i--}}
{{--                        class='nav-icon far fa-address-book'></i> Contactos</a></li>--}}
        </ul>
    </li>
@endif

@if(backpack_auth()->user()->hasRole(['developer', 'administrador' , 'logistica']))
    <!--Instalaciones-->
    <li class="nav-item nav-dropdown">
        <a class="nav-link nav-dropdown-toggle" href="void(0)"><i class="nav-icon fas fa-user-hard-hat"></i> Instalaciones</a>
        <ul class="nav-dropdown-items">
            <li class="nav-item"><a class="nav-link" href="{{ backpack_url('tarea') }}"><i
                        class="nav-icon fal fa-tasks"></i> Dashboard</a>
            </li>
            <li class="nav-item"><a class="nav-link" href="{{ backpack_url('installer') }}"><i
                        class="nav-icon fas fa-user-hard-hat"></i> Instaladores</a>
            </li>
            <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="javascript:void(0)"><i class="nav-icon far fa-sliders-v-square""></i> Settings</a>
                <ul class="nav-dropdown-items">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ backpack_url('installationtype') }}"><i class="nav-icon far fa-user-hard-hat"></i> <span>Tipo de instalación</span></a>
                    </li>
                </ul>
            </li>
        </ul>
    </li>
@endif
@if(backpack_auth()->user()->hasRole('developer'))
    <!--Sistema-->
    <li class="nav-item nav-dropdown">
        <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon fas fa-laptop"></i> Sistema</a>
        <ul class="nav-dropdown-items">
            <li class='nav-item'><a class='nav-link' href='{{ backpack_url('notificaciones') }}'><i
                        class="nav-icon fal fa-bell"></i> Notifications</a></li>
            <li class='nav-item'><a class='nav-link' href='{{ backpack_url('tipos-tareas') }}'><i
                        class="nav-icon far fa-tasks"></i> Tipo de Tareas</a></li>
            <li class='nav-item'><a class='nav-link' href='{{ backpack_url('ejecutivo') }}'><i
                        class="nav-icon far fa-user-tie"></i> Ejecutivos</a></li>
            <li class='nav-item'><a class='nav-link' href='{{ backpack_url('tipo-de-identificacion') }}'><i
                        class='nav-icon far fa-id-card-alt'></i>ID Types</a></li>
            <li class='nav-item'><a class='nav-link' href='{{ backpack_url('zona') }}'><i
                        class="nav-icon fa fal fa-map-marker-alt"></i> Zonas</a></li>
            <li class='nav-item'><a class='nav-link' href='{{ backpack_url('ruta') }}'><i
                        class="nav-icon fa fal fa-map-marker-alt"></i> Rutas</a></li>
        </ul>
    </li>
@endif

@can('advanced.authentication')
    <!-- Advanced -->
    <li class="nav-title">{{ trans('menu.advanced') }}</li>

    <!-- Users, Roles Permissions -->
    <li class="nav-item nav-dropdown">
        <a class="nav-link nav-dropdown-toggle" href="#"><i
                class="nav-icon  far fa-user-secret"></i>{{ trans('menu.authentication') }}</a>
        <ul class="nav-dropdown-items">
            <li class="nav-item"><a class="nav-link" href="{{ backpack_url('user') }}"><i
                        class="nav-icon fa fa-user"></i>
                    <span> {{ trans('backpack::permissionmanager.user_plural') }}</span></a></li>
            <li class="nav-item"><a class="nav-link" href="{{ backpack_url('role') }}"><i
                        class="nav-icon fas fa-users"></i>
                    <span> {{ trans('backpack::permissionmanager.roles') }}</span></a></li>
            <li class="nav-item"><a class="nav-link" href="{{ backpack_url('permission') }}"><i
                        class="nav-icon fa fa-key"></i>
                    <span> {{ trans('backpack::permissionmanager.permission_plural') }}</span></a></li>
        </ul>
    </li>
@endcan

@can('developer-tools')
    <!-- Developer -->
    <li class="nav-item nav-dropdown">
        <a class="nav-link nav-dropdown-toggle" href="#"><i
                class="nav-icon fa fa-cogs"></i>{{ trans('developer.developer') }}</a>
        <ul class="nav-dropdown-items">
            <li class=nav-item><a class=nav-link href="{{ backpack_url('log') }}"><i
                        class='nav-icon fa fa-terminal'></i><span>{{ trans('backpack::logmanager.log_manager') }}</span></a>
            </li>
        </ul>
    </li>

    @php
        $env_current = App::environment();
        $env_colors = ['local' => 'success', 'develop' => 'success', 'stage' => 'warning','production' => 'danger'];
    @endphp

    <!-- Environment -->
    <li class="nav-title">{{ trans('developer.environment') }}</li>
    <li class="nav-item d-compact-none d-minimized-none">
        <a class="nav-label" href="javascript:;">
            <i class="fa fa-circle text-{{$env_colors[$env_current]}}"></i> {{trans('developer.'.$env_current)}}
        </a>
    </li>
@endcan

