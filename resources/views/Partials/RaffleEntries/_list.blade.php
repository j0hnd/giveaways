@if (isset($entries))
    @foreach ($entries as $entry)
        @php
            if ($entry->is_winner) {
                $tr_background = "bg-winner";
            } else {
                $tr_background = "";
            }
        @endphp
    <tr class="{{ $tr_background }}" data-id="{{ $entry->raffle_entry_id }}" data-toggle="tooltip" data-placement="top" title="Winner for this raffle">
        <td>
            @if ($tr_background)
            <span class="glyphicon glyphicon-asterisk" aria-hidden="true"></span>
            @endif
            {{ $entry->email }}
            @if($entry->position)
            <span class="super">{{ $entry->position }}</span>
            @endif
        </td>
        <td>{{ $entry->code }}</td>
        <td>{{ $entry->action_name }}</td>
        <td class="text-center">{{ date('M. j, Y', strtotime($entry->created_at)) }}</td>
    </tr>
    @endforeach
@else
    <tr>
        <td colspan="5" class="text-center">No raffle entries found</td>
    </tr>
@endif
