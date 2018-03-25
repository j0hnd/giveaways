<div class="col-md-12">
    <div class="form-group">
        <label for="raffle-name">Item Name</label>
        <input type="text" class="form-control" id="item-name" name="name" placeholder="Item Name">
    </div>

    <div class="row">
        <div class="col-xs-3">
            <label for="amount">Amount</label>
            <input type="text" class="form-control text-right" id="amount" name="amount" value="1.00" placeholder="Amount">
        </div>

        <div class="col-xs-9">
            <label for="amount">Upload Image</label>
            {!! Form::file('image', array('class' => 'image')) !!}
        </div>
    </div>

    {!! Form::hidden('id', null, ['id' => 'id']) !!}
</div>
