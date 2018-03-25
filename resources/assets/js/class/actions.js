$(function () {
    var validation_rules = {
        name: {
            required: true
        }
    };

    var $btn;

    var submit_handler = function (form, url, type, modalObject) {
        $.ajax({
            url:      url,
            type:     type,
            data:     $(form).serialize(),
            dataType: 'json',
            success: function(response) {
                $btn.button('reset');

                if (response.success) {
                    $(form)[0].reset();

                    $('.success-message-container').removeClass('hidden');
                    $('.success-message-container').find('.message').text(response.message);
                    setTimeout(function() {
                        $('.success-message-container').addClass('hidden');
                        modalObject.modal('hide');

                        $.ajax({
                            url:       '/actions/reload/list',
                            dataType:  'json',
                            beforeSend: function () {
                                var loader = "<tr><td colspan='4' class='text-center padding-20'><i class='fa fa-cog fa-2x fa-spin' aria-hidden='true'></i><br>Loading...</td></tr>";
                                $('#action-list-container').html(loader);
                            },
                            success: function(response) {
                                $('#action-list-container').html(response.data.list);
                            }
                        });
                    }, 3000);
                }
            },
            error: function(data) {
                $btn.button('reset');

                var errors = data.responseJSON;
                var error_message = "";
                $.each(errors, function(key, value) {
                    if (value !== undefined) {
                        error_message += "<li>"+ value +"</li>";
                    }
                });

                $('.error-wrapper').append(error_message);
                $('.error-container').removeClass('hidden');
                setTimeout(function () {
                    $('.error-container').addClass('hidden');
                    $('.error-wrapper').html('');
                }, 5000);
            }
        });
    };

    $('#action-form').validate({
        rules: validation_rules,
        submitHandler: function (form) {
            submit_handler(form, '/actions', 'post', $('#addActionModal'));
        }
    });

    $('#edit-action-form').validate({
        rules: validation_rules,
        submitHandler: function (form) {
            submit_handler(form, '/actions/' + $('#id').val(), 'put', $('#editActionModal'));
        }
    });


    $('.action').select2();

    $(document).on('click', '#toggle-add-action', function (e) {
        $('#addActionModal').modal({
            backdrop: 'static',
            keyboard: false
        });
    });

    $(document).on('click', '#toggle-edit-action', function (e) {
        $('#editActionModal').modal({
            backdrop: 'static',
            keyboard: false
        });

        $('.modal-body').find('#id').val($(this).data('id'));
        $('.modal-body').find('#action-name').val($(this).data('name'));

        if ($(this).data('default')) {
            $('.modal-body').find('#default').prop('checked', 'checked');
        } else {
            $('.modal-body').find('#default').prop('checked', '');
        }
    });

    $(document).on('click', '#toggle-action-update', function(e) {
        $btn = $(this).button('loading');
        $('#edit-action-form').submit();
    });

    $(document).on('click', '#toggle-delete-action', function (e) {
        var aid = $(this).data('id');
        var aname = $(this).data('name');

        bootbox.confirm({
            message: "<div class='alert alert-warning margin-top10' role='alert'><strong>Warning!</strong><h4>Delete "+ aname +" action?</h4></div>",
            buttons: {
                confirm: {
                    label: 'Yes',
                    className: 'btn-danger'
                },
                cancel: {
                    label: 'No',
                    className: 'btn-link'
                }
            },
            callback: function (result) {
                if (result) {
                    $.ajax({
                        url:       '/actions/' + aid,
                        type:      'delete',
                        dataType:  'json',
                        beforeSend: function () {
                            var loader = "<tr><td colspan='4' class='text-center padding-20'><i class='fa fa-cog fa-2x fa-spin' aria-hidden='true'></i><br>Loading...</td></tr>";
                            $('#action-list-container').html(loader);
                        },
                        success:    function (response) {
                            if (response.success) {
                                var message = "<div class='alert alert-success margin-top10' role='alert'><strong>Well done!</strong><h4>"+ response.message +"</h4></div>";
                            } else {
                                var message = "<div class='alert alert-danger margin-top10' role='alert'><strong>Oh snap!</strong><h4>"+ response.message +"</h4></div>";
                            }

                            bootbox.alert({
                                message: message
                            });

                            $('#action-list-container').html(response.data.list);
                        }
                    });
                }
            }
        });
    });

    $(document).on('click', '#toggle-submit-action', function (e) {
        $btn = $(this).button('loading');
        $('#action-form').submit();
    });
});