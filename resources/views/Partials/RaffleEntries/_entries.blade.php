<div class="row">
    <div class="col-md-12">
        <table class="table table-striped">
            <thead>
                <th class="col-xs-5">Email</th>
                <th class="col-xs-2">Code</th>
                <th class="col-xs-3">Action</th>
                <th class="col-xs-2 text-center">Date Joined</th>
            </thead>

            <tbody id="raffle-entries-list-container">
                @include('Partials.RaffleEntries._list', compact('entries'))
            </tbody>

            @if (isset($entries))
            <tfoot>
                <tr>
                    <td colspan="4">{{ $entries->links() }}</td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
</div>
