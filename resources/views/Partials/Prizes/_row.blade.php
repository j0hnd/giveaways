<tr id="row-{{ $prize->raffle_prize_id }}">
    <td class="col-xs-9">{{ $prize->name }}</td>
    <td class="col-xs-3">
        <input type="hidden" name="selected[raffle_prize_id][]" value="{{ $prize->raffle_prize_id }}" />
        <input type="number" class="form-control order" name="selected[order][]" value="0" />
    </td>
    <td>
        <button type="button" id="toggle-delete-selected-prize" class="btn btn-danger" data-id="{{ $prize->raffle_prize_id }}" data-name="{{ $prize->name }}" data-action="">
            <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
        </button>
    </td>
</tr>