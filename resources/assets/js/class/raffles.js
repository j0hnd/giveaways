$(function() {
    var selected = [];
    var td_row;
    var $btn;

    var reload_raffle = function () {
        $.ajax({
            url: '/raffle/reload/list',
            dataType: 'json',
            beforeSend: function () {
                var loader = "<tr><td colspan='4' class='text-center padding-20'><i class='fa fa-cog fa-2x fa-spin' aria-hidden='true'></i><br>Loading...</td></tr>";
                $('#raffle-list-container').html(loader);
            },
            success: function(response) {
                $('#raffle-list-container').html(response.data.list);
            }
        });
    };

    var submit_handler = function (form, url, modalObject) {
        if (CKEDITOR.instances['raffle-description'] != undefined) {
            if (CKEDITOR.instances['description'].getData()) {
                $('#description').text(CKEDITOR.instances['description'].getData());
            }
        }

        if (CKEDITOR.instances['edit-description'] != undefined) {
            if (CKEDITOR.instances['edit-description'].getData()) {
                $('#edit-description').text(CKEDITOR.instances['edit-description'].getData());
            }
        }

        if (CKEDITOR.instances['mechanics'] != undefined) {
            if (CKEDITOR.instances['mechanics'].getData()) {
                $('#mechanics').text(CKEDITOR.instances['mechanics'].getData());
            }
        }

        if (CKEDITOR.instances['edit-mechanics'] != undefined) {
            if (CKEDITOR.instances['edit-mechanics'].getData()) {
                $('#edit-mechanics').text(CKEDITOR.instances['edit-mechanics'].getData());
            }
        }


        $.ajax({
            url: url,
            type: 'post',
            data: $(form).serialize(),
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
                        reload_raffle();
                    }, 3000);
                } else {
                    $('.success-message-container').removeClass('hidden');
                    $('.success-message-container').removeClass('alert-success');
                    $('.success-message-container').addClass('alert-danger');
                    $('.success-message-container').find('#message-prefix').text('Oh snap!');
                    $('.success-message-container').find('.message').text(response.message);
                    setTimeout(function() {
                        $('.success-message-container').addClass('hidden');
                    }, 3000);
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
                }, 5000);
            }
        });
    };

    function get_selected_prizes () {
        var rows = $('#raffle-prizes').find('> tbody > tr');

        // reset
        selected = [];

        $.each(rows, function (k, v) {
            var attr_id = $(v).attr('id');
            if (attr_id != undefined) {
                var id = attr_id.split('row-');
                selected.push(id[1]);
            }
        });
    }

    function get_selected_actions () {
        var rows = $('#raffle-actions').find('> tbody > tr');

        // reset
        selected = [];

        $.each(rows, function (k, v) {
            var attr_id = $(v).attr('id');
            if (attr_id != undefined) {
                var id = attr_id.split('row-');
                selected.push(id[1]);
            }
        });
    }

    function getPosts(page) {
        $.ajax({
            url: page,
            dataType: 'json',
        }).done(function (response) {
            $('#raffle-entries-container').html(response.data.list);
        }).fail(function () {
            alert('Raffle entries could not be loaded.');
        });
    }

    $("body").delegate(".datepicker", "focusin", function(){
        $(this).datepicker();
    });

    $("body").delegate(".timepicker", "focusin", function(){
        $(this).timepicker();
    });

    $('.modal-body').find('#prize-name').select2();

    $('#raffleAddModal').on( "show.bs.modal", function() {
        $(document).off('focusin.modal');
    });

    $('#raffleEditModal').on( "show.bs.modal", function() {
        $(document).off('focusin.modal');
    });

    $(document).on('click', '#toggle-actions-raffle', function(e) {
        $('#raffleActionModal').modal({
            backdrop: 'static',
            keyboard: false
        });

        $('.modal-body').find('#raffle-id').val($(this).data('id'));
        $('.modal-body').find('#raffle-name').text($(this).data('name'));

        var status = $(this).data('status');

        if (status == 'ended') {
            $('.modal-body').find('form').find('div.form-group:eq(1)').hide();
            $('.modal-footer').find('#toggle-update-action').hide();
        } else {
            $('.modal-body').find('form').find('div.form-group:eq(1)').show();
            $('.modal-footer').find('#toggle-update-action').show();
        }

        // populate asssigned actions
        $.ajax({
            url:       '/actions/assign/list/' + $(this).data('id'),
            dataType:  'json',
            beforeSend: function () {
                var loader = "<tr class='no-hover'><td colspan='2' class='text-center padding-20'><i class='fa fa-cog fa-2x fa-spin' aria-hidden='true'></i><br>Loading...</td></tr>";
                $('#selected-actions-container').html(loader);
            },
            success:    function (response) {
                if (response.success) {
                    $('.modal-body').find('#selected-actions-container').html(response.data.row);
                    if (status == 'ended') {
                        $('.modal-body').find('#toggle-delete-selected-action').hide();
                    } else {
                        $('.modal-body').find('#toggle-delete-selected-action').show();
                    }

                    // collect selected actions
                    get_selected_actions();
                }
            }
        });
    });

    $('#raffle-form').validate({
        ignore: [],
        rules: {
            name: {
                required: true
            },
            subtitle: {
                required: true
            },
            description: {
                required: function () {
                    CKEDITOR.instances['description'].updateElement();
                },
                minlength: 80
            },
            mechanics: {
                required: function () {
                    CKEDITOR.instances['mechanics'].updateElement();
                },
                minlength: 80
            },
            number_of_winners: {
                required: true,
                digits: true,
                min: 1
            },
            start_date: {
                required: true,
                date: true,
            },
            end_date: {
                required: true,
                date: true,
            }
        },
        errorPlacement: function(error, element) {
            $btn.button('reset');
            error.appendTo( element.parent("div").next("div") );
        },
        submitHandler: function (form) {
            submit_handler(form, '/raffle', $('#raffleAddModal'));
            return false;
        }
    });

    $('#edit-raffle-form').validate({
        ignore: [],
        rules: {
            name: {
                required: true
            },
            subtitle: {
                required: true
            },
            description: {
                required: function () {
                    CKEDITOR.instances['edit-description'].updateElement();
                },
                minlength: 80
            },
            mechanics: {
                required: function () {
                    CKEDITOR.instances['edit-mechanics'].updateElement();
                },
                minlength: 80
            },
            number_of_winners: {
                required: true,
                digits: true,
                min: 1
            },
            start_date: {
                required: true,
                date: true,
            },
            end_date: {
                required: true,
                date: true,
            }
        },
        errorPlacement: function(error, element) {
            $btn.button('reset');
            error.appendTo( element.parent("div").next("div") );
        },
        submitHandler: function (form) {
            submit_handler(form, '/raffle/update', $('#raffleEditModal'));
            return false;
        }
    });

    $(document).on('click', '#toggle-update-action', function () {
        var el = $(this);
        $.ajax({
            url:      '/actions/assign',
            type:     'post',
            data:     $('#action-assign-form').serialize(),
            dataType: 'json',
            beforeSend: function () {
                $btn = el.button('loading');
            },
            success:   function (response) {
                el.button('reset');
                $('.success-message-container').removeClass('hidden');
                $('.success-message-container').find('.message').text(response.message);
                setTimeout(function() {
                    $('.success-message-container').addClass('hidden');

                    // reset array
                    selected = [];

                    // reset form
                    $('#action-assign-form')[0].reset();

                    // populate asssigned prize list
                    $.ajax({
                        url:       '/actions/assign/list/' + $('#raffle-id').val(),
                        dataType:  'json',
                        beforeSend: function () {
                            var loader = "<tr class='no-hover'><td colspan='2' class='text-center padding-20'><i class='fa fa-cog fa-2x fa-spin' aria-hidden='true'></i><br>Loading...</td></tr>";
                            $('#selected-actions-container').html(loader);
                        },
                        success:    function (response) {
                            if (response.success) {
                                $('.modal-body').find('#selected-actions-container').html(response.data.row);
                            }
                        }
                    });

                }, 3000);
            }
        });
    });

    $(document).on('click', '#toggle-prizes-raffle', function(e) {
        $('#rafflePrizesdModal').modal({
            backdrop: 'static',
            keyboard: false
        });

        $('.modal-body').find('#raffle-id').val($(this).data('id'));
        $('.modal-body').find('#raffle-name').text($(this).data('name'));

        var status = $(this).data('status');

        if (status == 'ended') {
            $('.modal-body').find('form').find('div.form-group:eq(1)').hide();
            $('.modal-footer').find('#toggle-assign-prize').hide();
        } else {
            $('.modal-body').find('form').find('div.form-group:eq(1)').show();
            $('.modal-footer').find('#toggle-assign-prize').show();
        }

        // populate asssigned prize list
        $.ajax({
            url:       '/prizes/assign/list/' + $(this).data('id'),
            dataType:  'json',
            beforeSend: function () {
                var loader = "<tr class='no-hover'><td colspan='3' class='text-center padding-20'><i class='fa fa-cog fa-2x fa-spin' aria-hidden='true'></i><br>Loading...</td></tr>";
                $('#selected-prizes-container').html(loader);
            },
            success:    function (response) {
                if (response.success) {
                    $('.modal-body').find('#selected-prizes-container').html(response.data.row);
                    if (status == 'ended') {
                        $('.modal-body').find('#toggle-delete-selected-prize').hide();
                    } else {
                        $('.modal-body').find('#toggle-delete-selected-prize').show();
                    }

                    // collect selected prizes
                    get_selected_prizes();
                }
            }
        });
    });

    $(document).on('click', '#toggle-create-raffle', function(e) {
        $('#raffleAddModal').modal({
            backdrop: 'static',
            keyboard: false
        });

        $.ajax({
            url:      '/raffle/create',
            dataType: 'json',
            beforeSend: function () {
                $('.modal-body').find('#raffle-form').html("<div class='text-center'><i class='fa fa-cog fa-2x fa-spin' aria-hidden='true'></i><br>Loading...</div>");
            },
            success: function (response) {
                $('.modal-body').find('#raffle-form').html(response.form);
            }
        });
    });

    $(document).on('click', '#toggle-select-prize', function (e) {
        var el = $(this);
        var selected_prize = $('#prize').val();
        if (selected_prize.length < 1) {
            var message = "<div class='alert alert-warning margin-top10' role='alert'><strong>Warning!</strong><h4>Please select a prize</h4></div>";
            bootbox.alert(message);
        } else {
            if ($.inArray(selected_prize, selected) == -1) {
                if ($('.no-prize').is(':visible')) {
                    $('#selected-prizes-container').find('tr.no-prize').remove();
                }

                $.ajax({
                    url:      '/prizes/selected/row/' + selected_prize,
                    dataType: 'json',
                    beforeSend: function () {
                        $btn = el.button('loading');
                    },
                    success:   function(response) {
                        $btn.button('reset');
                        if (response.success) {
                            if ($('#no-selected-prize').is(':visible')) {
                                $('#selected-prizes-container').html('');
                            }

                            selected.push(selected_prize);

                            $('#selected-prizes-container').append(response.data.row);
                            $('#selected-prizes-container').find('tr#row-' + selected_prize).find('input.order').val(selected.length);
                        }
                    }
                });
            } else {
                var message = "<div class='alert alert-warning margin-top10' role='alert'><strong>Warning!</strong><h4>Prize is already selected. Please choose another prize.</h4></div>";
                bootbox.alert(message);
            }
        }
    });

    $(document).on('click', '#toggle-select-action', function (e) {
        var el = $(this);
        var selected_action = $('#action').val();
        if (selected_action.length < 1) {
            var message = "<div class='alert alert-warning margin-top10' role='alert'><strong>Warning!</strong><h4>Please select an action</h4></div>";
            bootbox.alert(message);
        } else {
            if ($.inArray(selected_action, selected) == -1) {
                if ($('.no-action').is(':visible')) {
                    $('#selected-actions-container').find('tr.no-action').remove();
                }

                $.ajax({
                    url:      '/actions/selected/row/' + selected_action,
                    dataType: 'json',
                    beforeSend: function () {
                        $btn = el.button('loading');
                    },
                    success:   function(response) {
                        $btn.button('reset');
                        if (response.success) {
                            if ($('#no-selected-action').is(':visible')) {
                                $('#selected-actions-container').html('');
                            }

                            selected.push(selected_action);

                            $('#selected-actions-container').append(response.data.row);
                        }
                    }
                });
            } else {
                var message = "<div class='alert alert-warning margin-top10' role='alert'><strong>Warning!</strong><h4>Action is already selected. Please choose another one.</h4></div>";
                bootbox.alert(message);
            }
        }
    });

    $(document).on('click', '#toggle-delete-selected-action', function () {
        var row    = $(this).closest('tr');
        var rid    = $(this).data('rid');
        var id     = $(this).data('id');
        var name   = $(this).data('name');
        var action = $(this).data('action');

        bootbox.confirm({
            message: "<div class='alert alert-warning margin-top10' role='alert'><strong>Warning!</strong><h4>Remove "+ name +" from the list?</h4></div>",
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
                    if (action == 'submit') {
                        $.ajax({
                            url:       '/actions/delete/'+ rid +'/' + id,
                            type:      'delete',
                            data:      { _token: $('meta[name=csrf-token]').attr("content") },
                            dataType:  'json',
                            beforeSend: function () {
                                var loader = "<tr class='no-hover'><td colspan='2' class='text-center padding-20'><i class='fa fa-cog fa-2x fa-spin' aria-hidden='true'></i><br>Loading...</td></tr>";
                                $('#selected-actions-container').html(loader);
                            },
                            success:    function (response) {
                                $('.success-message-container').removeClass('hidden');
                                $('.success-message-container').find('.message').text(response.message);
                                $('#selected-actions-container').html(response.data.row);
                                setTimeout(function() {
                                    $('.success-message-container').addClass('hidden');
                                }, 3000);
                            }
                        });
                    } else {
                        selected.pop(id);
                        row.remove();
                    }
                }
            }
        });
    });

    $(document).on('click', '#toggle-assign-prize', function () {
        var el = $(this);
        $.ajax({
            url:      '/prizes/assign',
            type:     'post',
            data:     $('#prize-assign-form').serialize(),
            dataType: 'json',
            beforeSend: function () {
                $btn = el.button('loading');
            },
            success:   function (response) {
                $btn.button('reset');
                $('.success-message-container').removeClass('hidden');
                $('.success-message-container').find('.message').text(response.message);
                setTimeout(function() {
                    $('.success-message-container').addClass('hidden');

                    // reset array
                    selected = [];

                    // reset form
                    $('#prize-assign-form')[0].reset();

                    // populate asssigned prize list
                    $.ajax({
                        url:       '/prizes/assign/list/' + $('#raffle-id').val(),
                        dataType:  'json',
                        beforeSend: function () {
                            var loader = "<tr class='no-hover'><td colspan='3' class='text-center padding-20'><i class='fa fa-cog fa-2x fa-spin' aria-hidden='true'></i><br>Loading...</td></tr>";
                            $('#selected-prizes-container').html(loader);
                        },
                        success:    function (response) {
                            if (response.success) {
                                $('.modal-body').find('#selected-prizes-container').html(response.data.row);
                            }
                        }
                    });

                }, 3000);
            }
        });
    });

    $(document).on('click', '#toggle-delete-selected-prize', function () {
        var row    = $(this).closest('tr');
        var rid    = $(this).data('rid');
        var id     = $(this).data('id');
        var name   = $(this).data('name');
        var action = $(this).data('action');

        bootbox.confirm({
            message: "<div class='alert alert-warning margin-top10' role='alert'><strong>Warning!</strong><h4>Remove "+ name +" from the list?</h4></div>",
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
                    if (action == 'submit') {
                        $.ajax({
                            url:       '/prizes/delete/'+ rid +'/' + id,
                            type:      'delete',
                            data:      { _token: $('meta[name=csrf-token]').attr("content") },
                            dataType:  'json',
                            beforeSend: function () {
                                var loader = "<tr class='no-hover'><td colspan='3' class='text-center padding-20'><i class='fa fa-cog fa-2x fa-spin' aria-hidden='true'></i><br>Loading...</td></tr>";
                                $('#selected-prizes-container').html(loader);
                            },
                            success:    function (response) {
                                $('.success-message-container').removeClass('hidden');
                                $('.success-message-container').find('.message').text(response.message);
                                $('#selected-prizes-container').html(response.data.row);
                                setTimeout(function() {
                                    $('.success-message-container').addClass('hidden');
                                }, 3000);
                            }
                        });
                    } else {
                        selected.pop(id);
                        row.remove();
                    }
                }
            }
        });
    });

    $(document).on('click', '#toggle-draw-raffle', function(e) {
        var _id = $(this).data('id');
        bootbox.confirm({
            message: "<div class='alert alert-info margin-top10' role='alert'><h4>Are you sure you want to draw a winner for this raffle?</h4></div>",
            buttons: {
                confirm: {
                    label: 'Yes',
                    className: 'btn-success'
                },
                cancel: {
                    label: 'No',
                    className: 'btn-danger'
                }
            },
            callback: function (result) {
                if (result) {
                    $.ajax({
                        url: '/draw/' + _id,
                        type: 'post',
                        data: { _token: $('meta[name=csrf-token]').attr("content") },
                        dataType: 'json',
                        beforeSend: function () {
                            var loader = "<td colspan='4' class='text-center padding-20'><i class='fa fa-refresh fa-1x fa-spin margin-right10' aria-hidden='true'></i>Preparing raffle entries...</td>";
                            $('#row-' + _id).removeClass('bg-success');
                            $('#row-' + _id).addClass('bg-red');
                            $('#row-' + _id).html(loader);
                        },
                        success: function (response) {
                            if (response.success) {
                                var loader = "<td colspan='4' class='text-center padding-20'><i class='fa fa-refresh fa-1x fa-spin margin-right10' aria-hidden='true'></i>"+ response.message +"</td>";
                                $('#row-' + _id).html(loader);
                            } else {
                                bootbox.alert({
                                    message: "<div class='alert alert-danger margin-top10' role='alert'><h4>"+ response.message +"</h4></div>"
                                });

                                setTimeout(function () {
                                    $.ajax({
                                        url: '/raffle/reload/list',
                                        dataType: 'json',
                                        success: function (response) {
                                            $('#raffle-list-container').html(response.data.list);
                                        }
                                    });
                                }, 3000);
                            }
                        }
                    });
                }
            }
        });
    });

    $(document).on('click', '#toggle-raffle-entries', function(e) {

        $.ajax({
            url:      '/raffle-entries/' + $(this).data('id'),
            dataType: 'json',
            success: function (response) {
                $('#raffleEntriesModal').modal({
                    backdrop: 'static',
                    keyboard: false
                });

                $('.modal-body').find('#raffle-entries-container').html(response.data.list);
            }
        });
    });

    $(document).on('click', '#toggle-delete-raffle', function(e) {
        $('#raffleDeleteModal').modal({
            backdrop: 'static',
            keyboard: false
        });

        $('.modal-body').find('#id').val($(this).data('id'));
        $('.modal-body').find('#delete-message').html("Delete this <strong>"+$(this).data('name')+"</strong> raffle?");
    });

    $(document).on('click', '#toggle-edit-raffle', function(e) {
        $('#raffleEditModal').modal({
            backdrop: 'static',
            keyboard: false
        });

        $.ajax({
            url:       '/raffle/edit',
            data:     { id: $(this).data('id') },
            dataType:  'json',
            beforeSend: function () {
                $('.modal-body').find('#edit-raffle-form').html("<div class='text-center'><i class='fa fa-cog fa-2x fa-spin' aria-hidden='true'></i><br>Loading...</div>");
            },
            success: function (response) {
                $('.modal-body').find('#edit-raffle-form').html(response.form);
            }
        });
    });

    $(document).on('click', '#toggle-delete', function() {
        var el = $(this);
        $.ajax({
            url: 'raffle/' + $('#id').val(),
            type: 'delete',
            data: { _token: $('meta[name=csrf-token]').attr("content"), id: $('#id').val() },
            dataType: 'json',
            beforeSend: function () {
                $btn = el.button('loading');
            },
            success: function (response) {
                $btn.button('reset');
                if (response.success) {
                    $('.success-message-container').removeClass('hidden');
                    $('.success-message-container').find('.message').text(response.message);
                    setTimeout(function() {
                        $('.success-message-container').addClass('hidden');
                        $('#raffleDeleteModal').modal('hide');

                        reload_raffle();
                    }, 3000);
                }
            }
        });
    });

    $(document).on('click', '#toggle-submit', function() {
        $btn = $(this).button('loading');
        $('#raffle-form').submit();
    });

    $(document).on('click', '#toggle-update', function(e) {
        $btn = $(this).button('loading');
        $('#edit-raffle-form').submit();
    });

    $(document).on('click', '.pagination a', function (e) {
        getPosts($(this).attr('href'));
        e.preventDefault();
    });

    $(document).on('click', '#toggle-archive-raffle', function (e) {
        $.ajax({
            url: '/raffle/archive',
            type: 'post',
            data: { id: $(this).data('id') },
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    bootbox.alert({
                        message: "<div class='alert alert-success margin-top10' role='alert'><strong>Well done!</strong><h4>"+ response.message +"</h4></div>",
                        callback: function () {
                            reload_raffle();
                        }
                    });
                } else {
                    bootbox.alert({
                        message: "<div class='alert alert-danger margin-top10' role='alert'><strong>Oh snap!</strong><h4>"+ response.message +"</h4></div>",
                    });
                }
            }
        });
    });

    $(document).on('click', '#toggle-close-raffle', function (e) {
        $.ajax({
            url: '/raffle/closed',
            type: 'post',
            data: { id: $(this).data('id') },
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    bootbox.alert({
                        message: "<div class='alert alert-success margin-top10' role='alert'><strong>Well done!</strong><h4>"+ response.message +"</h4></div>",
                        callback: function () {
                            reload_raffle();
                        }
                    });
                } else {
                    bootbox.alert({
                        message: "<div class='alert alert-danger margin-top10' role='alert'><strong>Oh snap!</strong><h4>"+ response.message +"</h4></div>",
                    });
                }
            }
        });
    });

    $(document).on('click', '#toggle-raffle-search', function (e) {
        $.ajax({
            url:      '/raffle/search',
            type:     'post',
            data:     $('#search-form').serialize(),
            dataType: 'json',
            beforeSend: function () {
                var loader = "<tr class='no-hover'><td colspan='4' class='text-center padding-20'><i class='fa fa-cog fa-2x fa-spin' aria-hidden='true'></i><br>Loading...</td></tr>";
                $('#raffle-list-container').html(loader);
            },
            success: function(response) {
                $('#raffle-list-container').html(response.data.list);
            }
        });
    });

    $(document).on("submit", "#search-form", function(e){
        e.preventDefault();
        return  false;
    });

    $(document).on('keypress', '#search', function (e) {
        if (e.which === 13) {
            $('#toggle-raffle-search').trigger('click');
            return false;
        }
    });

    // table row controls
    $(document).on('mouseenter', '.table tr', function () {
        var id = $(this).data('id');
        var controls = $(this).next('#row-'+ id +'-controls').find('td').html();


        if ($(this).find('td:last-child').hasClass('summary')) {
            // get default value of last td of row
            td_row = $(this).find('td:last-child').html();
            $(this).find('td:last-child').css('line-height', '2.6');

            // changed value of last td's of current row
            $(this).find('td:last-child').html(controls);

            $(this).find('td:last-child').removeClass('summary');
            $(this).find('td:last-child').addClass('controls');
        }
    });

    $(document).on('mouseleave', '.table tr', function () {
        if ($(this).find('td:last-child').hasClass('controls')) {
            $(this).find('td:last-child').html(td_row);

            $(this).find('td:last-child').removeClass('controls');
            $(this).find('td:last-child').addClass('summary');
            $(this).find('td:last-child').css('line-height', '1.6');
        }
    });

    $(document).on('click', '.table tr', function(e){
        if ($(e.target).hasClass('raffle-url-wrapper') && !$(e.target).hasClass('closed')) {
            var selection = window.getSelection();
            var range = document.createRange();
            range.selectNodeContents(e.target);
            selection.removeAllRanges();
            selection.addRange(range);

            try {
                var successful = document.execCommand('copy');
                if(successful) {
                    bootbox.alert({
                        message: "<div class='alert alert-info margin-top10' role='alert'><strong>Heads up!</strong><h4>Raffle's URL is copied to your clipboard?</h4></div>"
                    });
                }
            } catch (err) {
                var message = "<div class='alert alert-danger margin-top10' role='alert'><strong>Oh snap!</strong><h6>"+ err +"</h6></div>";
                bootbox.alert(message);
            }
        }
    });

    $(document).on('change', '#toggle-auto-draw', function (e) {
        var _autoDraw = $(this).is(':checked');
        $.ajax({
            url: '/update/auto-draw',
            type: 'put',
            data: { auto_draw: _autoDraw },
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    alertify.success(response.message);
                } else {
                    alertify.warning('Unable to update auto draw');
                }
            }
        });
    });
});
