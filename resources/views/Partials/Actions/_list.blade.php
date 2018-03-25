@if(count($actions))
    @foreach($actions as $action)
    <tr id="row-{{ $action->action_id }}">
        <td>{{ $action->name }}</td>
        <td>
            @if($action->is_default)
            <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
            @endif
        </td>
        <td class="text-center">{{ date('M. d, Y', strtotime($action->created_at)) }}</td>
        <td>
            <button type="button" id="toggle-edit-action" class="btn btn-default" data-id="{{ $action->action_id }}" data-name="{{ $action->name }}" data-default="{{ $action->is_default }}" data-toggle="tooltip" data-placement="bottom" title="Edit action"><span class="glyphicon glyphicon-edit"></span></button>
            <button type="button" id="toggle-delete-action" class="btn btn-danger" data-id="{{ $action->action_id }}" data-name="{{ $action->name }}" data-toggle="tooltip" data-placement="bottom" title="Delete action"><span class="glyphicon glyphicon-trash"></span></button>
        </td>
    </tr>
    @endforeach
@else
<tr>
    <td class="text-center" colspan="4">No actions found</td>
</tr>
@endif