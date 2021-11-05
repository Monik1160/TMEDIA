@if ($entry->status >= 4 && (backpack_auth()->user()->can('design') && $entry->status < 6))
    <!-- Edit button group -->
    <div class="btn-group">
        <a href="{{ url($crud->route.'/'.$entry->getKey().'/diseno') }}" class="btn btn-sm btn-link pr-0"><i
                class="fa fa-edit"></i> Diseño</a>
    </div>
@endif
