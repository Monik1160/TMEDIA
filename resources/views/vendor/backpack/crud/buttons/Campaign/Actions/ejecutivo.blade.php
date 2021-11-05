@if (backpack_auth()->user()->can('executive') && $entry->status < 6)
    <!-- Edit button group -->
    <div class="btn-group">
        <a href="{{ url($crud->route.'/'.$entry->getKey().'/ejecutivo') }}" class="btn btn-sm btn-link pr-0"><i
                class="fa fa-edit"></i> Ejecutivo</a>
    </div>
@endif
