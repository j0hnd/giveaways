<div class="col-md-12">
    <div class="form-group">
        <label for="raffle-name">Action Name</label>
        <input type="text" class="form-control" id="action-name" name="name" placeholder="Action Name">
    </div>

    <div class="form-group">
        <label for="default" class="margin-right10">Default</label>
        <input type="checkbox" id="default" name="default" value="1" />
    </div>

    {!! Form::hidden('id', null, ['id' => 'id']) !!}
</div>