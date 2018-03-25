<div id="addActionModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title">Add action</h4>
            </div>

            <div class="modal-body">
                @include('Partials.Common._flash')

                <div id="actions-form-container">
                    <div class="row">
                        <div class="col-md-12">
                            {!! Form::open(['id' => 'action-form', 'method' => 'post']) !!}
                            @include('Partials.Forms._action')
                            {!! Form::close() !!}
                        </div>

                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" id="" class="btn btn-link" data-dismiss="modal">Close</button>
                <button type="button" id="toggle-submit-action" class="btn btn-primary" data-loading-text="Saving...">Submit</button>
            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div id="editActionModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title">Edit action</h4>
            </div>

            <div class="modal-body">
                @include('Partials.Common._flash')

                <div id="edit-action-container">
                    <div class="row">
                        {!! Form::open(['id' => 'edit-action-form', 'method' => 'post']) !!}
                        @include('Partials.Forms._action')
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" id="" class="btn btn-link" data-dismiss="modal">Close</button>
                <button type="button" id="toggle-action-update" class="btn btn-primary" data-loading-text="Updating...">Update</button>
            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->