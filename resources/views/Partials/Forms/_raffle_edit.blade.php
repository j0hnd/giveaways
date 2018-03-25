<div class="col-md-12">
    <div class="form-group">
        <div>
            <label for="raffle-name">Raffle Name</label>
            <input type="text" class="form-control" id="raffle-name" name="name" placeholder="Raffle Name" value="{{ $raffle->name }}">
        </div>
        <div class="errorField"></div>
    </div>

    <div class="row form-group">
        <div class="col-md-9">
            <div>
                <label for="subtitle">Subtitle</label>
                <input type="text" class="form-control" id="subtitle" name="subtitle" placeholder="Subtitle" value="{{ $raffle->subtitle }}">
            </div>
            <div class="errorField"></div>
        </div>

        <div class="col-xs-3">
            <div>
                <label for="no-of-winners">No. of Winners</label>
                <input type="number" class="form-control text-right" id="no-of-winners" name="number_of_winners" placeholder="No. of Winners" value="{{ $raffle->number_of_winners }}">
            </div>
            <div class="errorField"></div>
        </div>
    </div>

    <div class="form-group">
        <div>
            <label for="description">Description</label>
            <textarea name="description" id="edit-description" class="form-control editor" rows="8" cols="80">{{ $raffle->description }}</textarea>
            @ckeditor('edit-description', [
                'height' => '150',
                'toolbar' => [
                    ['Cut','Copy','Paste','PasteText','PasteFromWord','-', 'SpellChecker', 'Scayt'],
                    ['Undo','Redo'],
                    ['Bold','Italic','Underline','Strike','-','Subscript','Superscript'],
                    ['NumberedList','BulletedList','-','Outdent','Indent','Blockquote'],
                    ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
                    ['Styles','BGColor']
                ]
            ])
        </div>
        <div class="errorField"></div>
    </div>

    <div class="form-group">
        <div>
            <label for="description">Mechanics</label>
            <textarea name="mechanics" id="edit-mechanics" class="form-control editor" rows="8" cols="80">{{ $raffle->mechanics }}</textarea>
            @ckeditor('edit-mechanics', [
                'height' => '150',
                'toolbar' => [
                    ['Cut','Copy','Paste','PasteText','PasteFromWord','-', 'SpellChecker', 'Scayt'],
                    ['Undo','Redo'],
                    ['Bold','Italic','Underline','Strike','-','Subscript','Superscript'],
                    ['NumberedList','BulletedList','-','Outdent','Indent','Blockquote'],
                    ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
                    ['Styles','BGColor']
                ]
            ])
        </div>
        <div class="errorField"></div>
    </div>

    @php
        $start_date = date('m/d/Y', strtotime($raffle->start_date));
        $start_time = date('h:i A', strtotime($raffle->start_date));

        $end_date = date('m/d/Y', strtotime($raffle->end_date));
        $end_time = date('h:i A', strtotime($raffle->end_date));
    @endphp

    <div class="row margin-top10">
        <div class="col-xs-6">
            <label for="start-date">Start Date</label>
            <div class="input-group date">
                <input type="text" class="form-control datepicker col-xs-2" id="start-date" name="start_date" placeholder="mm/dd/yyyy" value="{{ $start_date }}">
                <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                </span>
                <input type="text" class="form-control margin-left5 timepicker" id="start-time" name="start_time" placeholder="hh:mm AM/PM" value="{{ $start_time }}">
            </div>
            <div class="errorField"></div>
        </div>

        <div class="col-xs-6">
            <label for="end-date">End Date</label>
            <div class="input-group date">
                <input type="text" class="form-control datepicker" id="end-date" name="end_date" placeholder="mm/dd/yyyy" value="{{ $end_date }}">
                <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                </span>
                <input type="text" class="form-control margin-left5 timepicker" id="end-time" name="end_time" placeholder="hh:mm AM/PM" value="{{ $end_time }}">
            </div>
            <div class="errorField"></div>
        </div>
    </div>

    {!! csrf_field() !!}

    {!! Form::hidden('id', $raffle->raffle_id, ['id' => 'id']) !!}
</div>