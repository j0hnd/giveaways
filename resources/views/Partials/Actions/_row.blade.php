<tr id="row-{{ $action->action_id }}">
    <td class="col-xs-9">{{ $action->name }}</td>
    <td>
        <button type="button" id="toggle-delete-selected-action" class="btn btn-danger" data-id="{{ $action->action_id }}" data-name="{{ $action->name }}" data-action="">
            <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
        </button>

        <input type="hidden" name="selected[action_id][]" value="{{ $action->action_id }}" />
    </td>
</tr>