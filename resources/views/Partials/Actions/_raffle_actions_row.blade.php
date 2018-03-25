@if(count($actions))
    @foreach($actions as $action)
        <tr id="row-{{ $action['raffle_action_id'] }}">
            <td class="col-xs-9">{{ $action['name'] }}</td>
            <td>
                <button type="button" id="toggle-delete-selected-action" class="btn btn-danger" data-rid="{{ $action['raffle_id'] }}" data-id="{{ $action['raffle_action_id'] }}" data-name="{{ $action['name'] }}" data-action="submit">
                    <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                </button>
            </td>
        </tr>
    @endforeach
@else
    <tr class="no-action">
        <td class="text-center" colspan="3">No actions assigned</td>
    </tr>
@endif