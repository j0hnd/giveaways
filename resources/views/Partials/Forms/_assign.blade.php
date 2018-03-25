<form id="prize-assign-form" class="form-horizontal padding-10">
    <div class="form-group">
        <label class="col-sm-2">Raffle</label>
        <div class="col-sm-10">
            <p id="raffle-name"></p>
        </div>
    </div>

    <div id="prize-wrapper  " class="form-group">
        <label class="col-sm-2">Prizes</label>
        <div class="col-sm-10">
            <select id="prize" class="form-control prize">
                <option value="">Select Prize</option>
                @foreach($prize_list as $prize)
                <option value="{{ $prize->raffle_prize_id }}">{{ $prize->name }}</option>
                @endforeach
            </select>
            <button type="button" id="toggle-select-prize" class="btn btn-success margin-top25" data-loading-text="Selecting...">Select Prize</button>
        </div>
    </div>

    <table id="raffle-prizes" class="table">
        <thead>
            <tr>
                <th class="col-xs-8">Prize</th>
                <th class="col-xs-3">Order</th>
                <th class="col-xs-1"></th>
            </tr>
        </thead>
        <tbody id="selected-prizes-container">
            <tr id="no-selected-prize">
                <td colspan="3" class="text-center">No selected prize</td>
            </tr>
        </tbody>
    </table>

    <input type="hidden" id="raffle-id" name="selected[raffle_id]" />
    {{ csrf_field() }}
</form>

<style type="text/css">
    .select2-container {
        width: 100% !important;
    }
</style>