$(function() {

    var validation_rules = {
      name: {
          required: true
      },
      amount: {
          required: true,
          range: [1, 999999]
      }
    };

    var $btn;

    var submit_handler = function (form, url, modalObject) {
        $.ajax({
            url:      url,
            type:     'post',
            data:      new FormData($(form)[0]),
            cache:     false,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(response) {
                $btn.button('reset');

                if (response.success) {
                    $(form)[0].reset();

                    $('.success-message-container').removeClass('hidden');
                    $('.success-message-container').find('.message').text(response.message);
                    if (response.success == false) {
                        $('.success-message-container').removeClass('alert-success');
                        $('.success-message-container').addClass('alert-danger');
                        $('.success-message-container').find('#message-prefix').html('Warning!');

                        setTimeout(function() {
                            $('.success-message-container').addClass('hidden');
                        }, 3000);
                    } else {
                        $('.success-message-container').removeClass('alert-danger');
                        $('.success-message-container').addClass('alert-success');
                        $('.success-message-container').find('#message-prefix').html('Success!');

                        setTimeout(function() {
                            $('.success-message-container').addClass('hidden');
                            modalObject.modal('hide');

                            $.ajax({
                                url:       '/prizes/reload/list',
                                dataType:  'json',
                                beforeSend: function () {
                                    var loader = "<tr class='no-hover'><td colspan='4' class='text-center padding-20'><i class='fa fa-cog fa-2x fa-spin' aria-hidden='true'></i><br>Loading...</td></tr>";
                                    $('#prize-list-container').html(loader);
                                },
                                success: function(response) {
                                    if ($('#prize-list-container').is(':visible')) {
                                        $('#prize-list-container').html(response.data.list);
                                    } else {
                                        bootbox.alert({
                                            message: response.message,
                                            size: 'small'
                                        });
                                    }

                                }
                            });
                        }, 3000);
                    }
                }
            },
            error: function(data) {
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
    }

    $('.prize').select2();

    $('#prizeAddModal').on( "show.bs.modal", function() {
        $(document).off('focusin.modal');
    });

    $('#prizeEditModal').on( "show.bs.modal", function() {
        $(document).off('focusin.modal');
    });

    // Prevent Bootstrap dialog from blocking focusin
    $(document).on('focusin', function(e) {
        if ($(e.target).closest(".mce-window").length) {
            e.stopImmediatePropagation();
        }
    });

    $(document).on('click', '#toggle-add-prize', function() {
        $('#prizeAddModal').modal({
            backdrop: 'static',
            keyboard: false
        });

        $.ajax({
            url:      '/prizes/create',
            dataType: 'json',
            beforeSend: function () {
                $('.modal-body').find('#prize-form').html("<div class='text-center'><i class='fa fa-cog fa-2x fa-spin' aria-hidden='true'></i><br>Loading...</div>");
            },
            success: function (response) {
                $('.modal-body').find('#prize-form').html(response.form);
            }
        });
    });

    $(document).on('click', '#toggle-delete-prize', function(e) {
        $('#prizeDeleteModal').modal({
            backdrop: 'static',
            keyboard: false
        });

        $('.modal-body').find('#id').val($(this).data('id'));
        $('.modal-body').find('#delete-message').html("Delete this <strong>"+$(this).data('name')+"</strong> prize?");
    });

    $(document).on('click', '#toggle-edit-prize', function(e) {
        $('#prizeEditModal').modal({
            backdrop: 'static',
            keyboard: false
        });

        $.ajax({
            url:       '/prizes/edit',
            data:     { id: $(this).data('id') },
            dataType:  'json',
            beforeSend: function () {
                $('.modal-body').find('#edit-prize-form').html("<div class='text-center'><i class='fa fa-cog fa-2x fa-spin' aria-hidden='true'></i><br>Loading...</div>");
            },
            success: function (response) {
                $('.modal-body').find('#edit-prize-form').html(response.form);
            }
        });
    });

    $('#edit-prize-form').validate({
        rules: validation_rules,
        submitHandler: function (form) {
            submit_handler(form, '/prizes/updates', $('#prizeEditModal'));
            return false;
        }
    });

    $(document).on('click', '#toggle-prize-update', function(e) {
        $btn = $(this).button('loading');
        $('#edit-prize-form').submit();
    });

    $(document).on('click', '#toggle-prize-delete', function() {
        var el = $(this);
        $.ajax({
            url:      '/prizes/' + $('#id').val(),
            type:     'delete',
            data:     { _token: $('meta[name=csrf-token]').attr("content"), id: $('#id').val() },
            dataType: 'json',
            beforeSend: function () {
                $btn = el.button('loading');
            },
            success:   function (response) {
                $btn.button('reset');
                if (response.success) {
                    $('.success-message-container').removeClass('hidden');
                    $('.success-message-container').find('.message').text(response.message);
                    setTimeout(function() {
                        $('.success-message-container').addClass('hidden');
                        $('#prizeDeleteModal').modal('hide');

                        $.ajax({
                            url:       '/prizes/reload/list',
                            dataType:  'json',
                            beforeSend: function () {
                                var loader = "<tr class='no-hover'><td colspan='4' class='text-center padding-20'><i class='fa fa-cog fa-2x fa-spin' aria-hidden='true'></i><br>Loading...</td></tr>";
                                $('#prize-list-container').html(loader);
                            },
                            success: function(response) {
                                $('#prize-list-container').html(response.data.list);
                            }
                        });
                    }, 3000);
                }
            }
        });
    });

    $('#prize-form').validate({
        rules: validation_rules,
        submitHandler: function (form) {
            submit_handler(form, '/prizes', $('#prizeAddModal'));
            return false;
        }
    });

    $(document).on('click', '#toggle-submit-prizes', function () {
        $btn = $(this).button('loading');
        $('#prize-form').submit();
    });

    $(document).on('click', '#toggle-prize-search', function (e) {
        $.ajax({
            url:      '/prizes/search',
            type:     'post',
            data:     $('#search-form').serialize(),
            dataType: 'json',
            beforeSend: function () {
                var loader = "<tr class='no-hover'><td colspan='4' class='text-center padding-20'><i class='fa fa-cog fa-2x fa-spin' aria-hidden='true'></i><br>Loading...</td></tr>";
                $('#prize-list-container').html(loader);
            },
            success: function(response) {
                $('#prize-list-container').html(response.data.list);
            }
        });
    });
});
