@if(count($prizes))
    @foreach($prizes as $prize)
        <tr id="row-{{ $prize->raffle_prize_id }}">
            <td class="col-xs-9">{{ $prize->name }}</td>
            <td class="col-xs-3">
                <input type="hidden" name="selected[raffle_prize_id][]" value="{{ $prize->raffle_prize_id }}" />
                <input type="number" class="form-control order" name="selected[order][]" value="{{ $prize->order }}" />
            </td>
            <td>
                <button type="button" id="toggle-delete-selected-prize" class="btn btn-danger" data-rid="{{ $prize->raffle_id }}" data-id="{{ $prize->raffle_prize_id }}" data-name="{{ $prize->name }}" data-action="submit">
                    <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                </button>
            </td>
        </tr>
    @endforeach
@else
    <tr class="no-prize">
        <td class="text-center" colspan="3">No prizes assigned</td>
    </tr>
@endif