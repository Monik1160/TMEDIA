@if ($entry->status >= 2 )
    <!-- Edit button group -->
    <div class="btn-group">
        <a href="{{ url($crud->route.'/'.$entry->getKey().'/progress') }}" class="btn btn-sm btn-link pr-0"><i
                class="fal fa-bus"></i> Ver progreso</a>
    </div>
@endif
