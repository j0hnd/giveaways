@if (count($prizes))
    @foreach ($prizes as $prize)
    <tr data-id="{{ $prize->raffle_prize_id }}">
        <td>{{ $prize->name }}</td>
        <td class="text-right">{{ number_format($prize->amount, 2, '.', ',') }}</td>
        <td class="text-center">{{ date('M. j, Y', strtotime($prize->created_at)) }}</td>
        <td class="text-center">
            <button type="button" id="toggle-edit-prize" class="btn btn-default" data-id="{{ $prize->raffle_prize_id }}" data-name="{{ $prize->name }}" data-description="{{ $prize->description }}" data-rules="{{ $prize->rules }}" data-amount="{{ $prize->amount }}" data-toggle="tooltip" data-placement="bottom" title="Edit prize"><span class="glyphicon glyphicon-edit"></span></button>
            <button type="button" id="toggle-delete-prize" class="btn btn-danger" data-id="{{ $prize->raffle_prize_id }}" data-name="{{ $prize->name }}" data-toggle="tooltip" data-placement="bottom" title="Delete prize"><span class="glyphicon glyphicon-trash"></span></button>
        </td>
    </tr>
    @endforeach
@else
    <tr>
        <td colspan="4" class="text-center">No raffle prizes found</td>
    </tr>
@endif
