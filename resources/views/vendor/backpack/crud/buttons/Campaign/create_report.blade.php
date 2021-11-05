@php 

$has_end_tasks = false ;

foreach($entry->task as $task){

    // If the tasks is end
    if($task->status == "5"){
        $has_end_tasks = true;
        break;
    }
}
@endphp


@if ( $entry->status >= 6 && $has_end_tasks)
    <a id="create_report_button_{{$entry->id}}" target="_blank" class="create_report_button" href="{{ route('campaign.create.report'  , [$entry->id])}}" data-toggle="tooltip" data-placement="top"
    title="Crear Reporte">
    <i class="far fa-file"></i>
    </a>
@endif