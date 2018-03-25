<div class="col-md-12">
    <div class="form-group">
        <label for="raffle-name">Item Name</label>
        <input type="text" class="form-control" id="item-name" name="name" placeholder="Item Name" value="{{ $prize->name }}">
    </div>

    <div class="row">
        <div class="col-xs-3">
            <label for="amount">Amount</label>
            <input type="text" class="form-control text-right" id="amount" name="amount" placeholder="Amount" value="{{ $prize->amount }}">
        </div>

        <div class="col-xs-9">
            <label for="amount">Upload Image</label>
            {!! Form::file('image', array('id' => 'image', 'class' => 'image')) !!}
        </div>
    </div>

    <div class="row">
        @if (!empty($prize->image))
        <div class="col-xs-12 padding-15">
            <img src="{{ url('/uploads/'.$prize->image) }}" alt="{{ $prize->name }}" class="image">
        </div>
        @endif
    </div>

    {{ csrf_field() }}

    {!! Form::hidden('id', $prize->raflle_prize_id, ['id' => 'id']) !!}
</div>
