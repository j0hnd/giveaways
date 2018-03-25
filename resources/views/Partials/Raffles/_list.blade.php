@if (count($raffles['data']))
    @foreach ($raffles['data'] as $raffle)
        @php
            if (strtotime('now') > strtotime($raffle['end_date']) and empty($raffle['closed_date'])) {
                $tr_background = "bg-danger raffle-ended";
                $td_css = "";
            } elseif (strtotime('now') >= strtotime($raffle['start_date']) and strtotime('now') <= strtotime($raffle['end_date']) and empty($raffle['closed_date'])) {
                $tr_background = "bg-success raffle-ongoing";
                $td_css = "";
            } elseif (!empty($raffle['closed_date'])) {
                $tr_background = "bg-ended";
                $td_css = "closed";
            } else {
                $tr_background = "";
                $td_css = "";
            }
        @endphp

    <tr id="row-{{ $raffle['raffle_id'] }}" class="{{ $tr_background }}" data-id="{{ $raffle['raffle_id'] }}" >
        <td class="{{ $td_css }}">{{ $raffle['raffle_name'] }}</td>
        <td class="{{ $td_css }} raffle-url-wrapper">
            @if (empty($raffle['closed_date']))
            {!! link_to($raffle['raffle_url'], $raffle['raffle_url'], ['target' => '_blank']) !!}
            @else
            {{ $raffle['raffle_url'] }}
            @endif
        </td>
        <td class="text-left {{ $td_css }}">
            <span class="duration-label duration-start"><strong>Start:</strong></span>{{ date('M d, Y', strtotime($raffle['start_date'])) }} {{ $raffle['start_time'] }}
            <span class="duration-label duration-end"><strong>End:</strong></span>{{ date('M d, Y', strtotime($raffle['end_date'])) }} {{ $raffle['end_time'] }}
        </td>
        <td class="summary text-center">
            <span class=" text-uppercase small">signups: [ {{ $raffle['summary']['total_signups'] }} ], entries: [ {{ $raffle['summary']['total_entries'] }} ]</span>
        </td>
    </tr>
    <tr id="row-{{ $raffle['raffle_id'] }}-controls" class="hidden controls">
        <td colspan="4" class="text-right">
            @if (empty($raffle['closed_date']))
                <button type="button" id="toggle-edit-raffle" class="btn btn-default" data-id="{{ $raffle['raffle_id'] }}" data-toggle="tooltip" data-placement="bottom" title="Edit">
                    <span class="glyphicon glyphicon-edit"></span>
                </button>
            @endif

            <button type="button" id="toggle-raffle-entries" class="btn btn-default" data-id="{{ $raffle['raffle_id'] }}" data-toggle="tooltip" data-placement="bottom" title="Raffle Entries"><span class="glyphicon glyphicon-th-list"></span></button>

            @if (empty($raffle['closed_date']))
                @if ($tr_background == 'bg-danger raffle-ended')
                <button type="button" id="toggle-draw-raffle" class="btn btn-success" data-id="{{ $raffle['raffle_id'] }}" data-toggle="tooltip" data-placement="bottom" title="Draw raffle"><span class="glyphicon glyphicon-retweet"></span></button>
                <button type="button" id="toggle-close-raffle" class="btn btn-warning" data-status="ended" data-id="{{ $raffle['raffle_id'] }}" data-name="{{ $raffle['raffle_name'] }}" data-toggle="tooltip" data-placement="bottom" title="Close Raffle"><span class="glyphicon glyphicon-eye-close"></span></button>
                @else
                <button type="button" id="toggle-actions-raffle" class="btn btn-default" data-status="ongoing" data-id="{{ $raffle['raffle_id'] }}" data-name="{{ $raffle['raffle_name'] }}" data-toggle="tooltip" data-placement="bottom" title="Actions"><span class="glyphicon glyphicon-log-in"></span></button>

                    @if (strtotime('now') >= strtotime($raffle['start_date']))
                    <button type="button" id="toggle-draw-raffle" class="btn btn-success" data-id="{{ $raffle['raffle_id'] }}" data-toggle="tooltip" data-placement="bottom" title="Draw raffle"><span class="glyphicon glyphicon-retweet"></span></button>
                    @endif

                <button type="button" id="toggle-prizes-raffle" class="btn btn-warning" data-status="ongoing" data-id="{{ $raffle['raffle_id'] }}" data-name="{{ $raffle['raffle_name'] }}" data-toggle="tooltip" data-placement="bottom" title="Prizes"><span class="glyphicon glyphicon-usd"></span></button>
                @endif
            @else
                <button type="button" id="toggle-actions-raffle" class="btn btn-default" data-status="ended" data-id="{{ $raffle['raffle_id'] }}" data-name="{{ $raffle['raffle_name'] }}" data-toggle="tooltip" data-placement="bottom" title="Actions"><span class="glyphicon glyphicon-log-in"></span></button>
                <button type="button" id="toggle-prizes-raffle" class="btn btn-default" data-status="ended" data-id="{{ $raffle['raffle_id'] }}" data-name="{{ $raffle['raffle_name'] }}" data-toggle="tooltip" data-placement="bottom" title="Prizes"><span class="glyphicon glyphicon-usd"></span></button>
                <button type="button" id="toggle-archive-raffle" class="btn btn-warning" data-status="ended" data-id="{{ $raffle['raffle_id'] }}" data-name="{{ $raffle['raffle_name'] }}" data-toggle="tooltip" data-placement="bottom" title="Archive"><span class="glyphicon glyphicon-save"></span></button>
            @endif

            <button type="button" id="toggle-delete-raffle" class="btn btn-danger" data-id="{{ $raffle['raffle_id'] }}" data-name="{{ $raffle['raffle_name'] }}" data-toggle="tooltip" data-placement="bottom" title="Delete raffle"><span class="glyphicon glyphicon-trash"></span></button>
        </td>
    </tr>
    @endforeach
@else
    <tr class="no-hover">
        <td colspan="5" class="text-center">No raffles found!</td>
    </tr>
@endif
