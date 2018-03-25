@extends('layouts.app')

@section('main-content')
    <div class="row">
        <h1>Raffle Winners</h1>
    </div>

    <div class="row">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th class="col-md-3">Raffle Name</th>
                    <th class="col-md-3">Winner</th>
                    <th class="col-md-2">Prize</th>
                    <th class="col-md-2 text-center">Raffle Code</th>
                    <th class="col-md-1 text-center">Drawn Date</th>
                </tr>
                </thead>
                <tbody id="raffle-winners-container">
                    @include('Partials.Raffles._list_winners', compact('winners'))
                </tbody>
                @if (count($winners))
                <tfoot>
                    <tr>
                        <td colspan="5" class="text-right">{{ $winners->links() }}</td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>

@endsection

@section('custom-js')
    <script src="{{ url('js/class/raffles.js') }}" type="text/javascript"></script>
    <script src="{{ url('js/plugins/bootbox.min.js') }}"></script>
@endsection
