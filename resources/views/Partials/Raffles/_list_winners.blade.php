@if (count($winners))
    @foreach ($winners as $winner)
    <tr data-id="{{ $winner->raffle_id }}">
        <td>{{ $winner->name }}</td>
        <td>{{ $winner->email }} <span class="super">{{ $winner->position }}</span></td>
        <td>{{ $winner->prize_name }}</td>
        <td class="text-center">{{ $winner->code }}</td>
        <td class="text-center">{{ date('M. j, Y', strtotime($winner->closed_date)) }}</td>
    </tr>
    @endforeach
@else
    <tr>
        <td colspan="4">No raffles has been drawn.</td>
    </tr>
@endif