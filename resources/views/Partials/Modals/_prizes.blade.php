<div id="prizeAddModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title">Add Prize</h4>
            </div>

            <div class="modal-body">
                @include('Partials.Common._flash')

                <div id="prizes-form-container">
                    <div class="row">
                        {!! Form::open(['id' => 'prize-form', 'method' => 'post', 'enctype' => 'multipart/form-data']) !!}
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" id="" class="btn btn-link" data-dismiss="modal">Close</button>
                <button type="button" id="toggle-submit-prizes" class="btn btn-primary" data-loading-text="Saving...">Submit</button>
            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div id="prizeDeleteModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title">Delete Prize</h4>
            </div>

            <div class="modal-body">
                @include('Partials.Common._flash')

                <div id="raffle-entries-container">
                    <div class="row">
                        <div class="col-xs-12">
                            <p id="delete-message"></p>
                        </div>
                    </div>
                </div>

                <input type="hidden" id="id" name="id">
            </div>

            <div class="modal-footer">
                <button type="button" id="" class="btn btn-link" data-dismiss="modal">Close</button>
                <button type="button" id="toggle-prize-delete" class="btn btn-danger" data-loading-text="Deleting...">Delete</button>
            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div id="prizeEditModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title">Edit Prize</h4>
            </div>

            <div class="modal-body">
                @include('Partials.Common._flash')

                <div id="edit-prize-container">
                    <div class="row">
                        {!! Form::open(['id' => 'edit-prize-form', 'method' => 'post', 'enctype' => 'multipart/form-data']) !!}
                        {{--@include('Partials.Forms._prize')--}}
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" id="" class="btn btn-link" data-dismiss="modal">Close</button>
                <button type="button" id="toggle-prize-update" class="btn btn-primary" data-loading-text="Updating...">Update</button>
            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->