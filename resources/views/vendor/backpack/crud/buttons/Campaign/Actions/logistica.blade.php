@if ($entry->status >= 3 && (backpack_auth()->user()->can('logistics') && $entry->status < 6) )
    <!-- Edit button group -->
    <div class="btn-group">
        <a href="{{ url($crud->route.'/'.$entry->getKey().'/logistica') }}" class="btn btn-sm btn-link pr-0"><i
                class="fa fa-edit"></i> Logistica</a>
    </div>
@endif
