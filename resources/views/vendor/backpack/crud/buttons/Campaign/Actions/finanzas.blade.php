@if ($entry->status >= 2 && (backpack_auth()->user()->can('finance') && $entry->status <= 6))
    <!-- Edit button group -->
    <div class="btn-group">
        <a href="{{ url($crud->route.'/'.$entry->getKey().'/finanzas') }}" class="btn btn-sm btn-link pr-0"><i
                class="fa fa-edit"></i> Finanzas</a>
    </div>
@endif
