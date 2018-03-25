<form id="action-assign-form" class="form-horizontal padding-10">
    <div class="form-group">
        <label class="col-sm-2">Raffle</label>
        <div class="col-sm-10">
            <p id="raffle-name"></p>
        </div>
    </div>

    <div id="action-wrapper  " class="form-group">
        <label class="col-sm-2">Actions</label>
        <div class="col-sm-10">
            <select id="action" class="form-control action">
                <option value="">Select Action</option>
                @foreach($action_list as $action)
                    <option value="{{ $action->action_id }}">{{ $action->name }}</option>
                @endforeach
            </select>
            <button type="button" id="toggle-select-action" class="btn btn-success margin-top25" data-loading-text="Selecting...">Select Action</button>
        </div>
    </div>

    <table id="raffle-actions" class="table">
        <thead>
        <tr>
            <th class="col-xs-8">Name</th>
            <th class="col-xs-1"></th>
        </tr>
        </thead>
        <tbody id="selected-actions-container">
        <tr id="no-selected-action">
            <td colspan="2" class="text-center">No selected action</td>
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