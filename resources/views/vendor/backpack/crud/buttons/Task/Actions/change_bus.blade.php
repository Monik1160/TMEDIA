@if ($entry->status == 7 && backpack_auth()->user()->can('logistics') )
    <!-- Edit button group -->
    <div class="btn-group">
        <a href="{{ url($crud->route.'/change-bus/'.$entry->getKey().'/change_bus') }}" class="btn btn-sm btn-link pr-0"><i
                class="fal fa-bus"></i> Cambio de Bus</a>
    </div>
@endif
