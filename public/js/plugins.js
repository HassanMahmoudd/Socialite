$(document).ready(function () {

    var statusId = 0;
    var editUrl = '';
    var token = '';
    var likeUrl = '';
    var getLikersUrl = '';
    var isPublic = '';

    $('.edit-status').on('click', function (event) {
        event.preventDefault();
        statusBody = $(this).parent().parent().siblings('.profile-status').text();
        statusBodyElement = $(this).parent().parent().siblings('.profile-status');
        statusId = statusBodyElement.data('statusId');
        editUrl = statusBodyElement.data('url');
        token = statusBodyElement.data('token');
        $('#post-body').val(statusBody);
        // console.log($(this).data('ispublic'));
        editStatusElement = $(this);
        if($(this).data('ispublic') == '1') {
            $('#is-public-modal').attr('checked', true);
            // console.log('checked');
        }
        else
            $('#is-public-modal').attr('checked', false);
        $('#edit-post-modal').modal();
    });

    $('#save-post-modal').on('click', function (event) {
        event.preventDefault();
        // var url = $('.edit-post-form').data('url');
        // var token = $('.edit-post-form').data('csrf');
        // console.log($('#is-public-modal').val());
        if($('#is-public-modal').is(":checked")) {
            isPublic = '1';
            $('#is-public-modal').val('1');
        }
        else {
            isPublic = '0';
            $('#is-public-modal').val('0');
        }
            $.ajax({
            method: 'POST',
            url: editUrl,
            data: new FormData($("#edit-post-form")[0]),
            processData: false,
            contentType: false,
            error: function(data){
                console.log('error');
                var errors = data.responseJSON;
                // console.log(errors['body']);
                // Render the errors with js ...
                if(errors['body']) {
                    $('#post-body').parent().addClass('has-error');
                    $('#post-body').after('<span class="help-block">' + errors['body'] + '</span>');
                }
                if(errors['image']) {
                    $('#edit-post-image').parent().addClass('has-error');
                    $('#edit-post-image').after('<span class="help-block">' + errors['image'] + '</span>');
                }

            }
        })
        .done(function(msg) {
            $(statusBodyElement).text(msg['new_body']);
            $(editStatusElement).data('ispublic', msg['is_public']);
            if(msg['new_image'] != '0') {
                var image = $(statusBodyElement).next().children().first().data('image');
                var asset = $(statusBodyElement).data('image');
                var imageSrc = asset + '/' + msg['new_image'];
                // console.log(imageSrc);
                if(image == null) {
                    $(statusBodyElement).after('<div><img class="profile-status-photo" src="" data-image="' + image + '"></div>');
                   // console.log('Im undefined');
                }
                $(statusBodyElement).next().children().first().attr('src', imageSrc);
            }

            $('#edit-post-modal').modal('hide');
            console.log('success');
        });


    });

    $('.edit-reply').on('click', function (event) {
        event.preventDefault();
        statusBody = $(this).parent().parent().siblings('.profile-status').text();
        statusBodyElement = $(this).parent().parent().siblings('.profile-status');
        statusId = statusBodyElement.data('statusId');
        editUrl = statusBodyElement.data('url');
        token = statusBodyElement.data('token');
        $('#reply-body').val(statusBody);
        $('#edit-reply-modal').modal();
    });

    $('#save-reply-modal').on('click', function (event) {
        event.preventDefault();
        // var url = $('.edit-post-form').data('url');
        // var token = $('.edit-post-form').data('csrf');
        $.ajax({
                method: 'POST',
                url: editUrl,
                data: {body: $('#reply-body').val(), statusId: statusId, _token: token},
                error: function(data){
                    console.log('error');
                    var errors = data.responseJSON;
                    // console.log(errors['body']);
                    // Render the errors with js ...

                    $('#reply-body').parent().addClass('has-error');
                    $('#reply-body').after('<span class="help-block">' + errors['body'] + '</span>');
                }
            })
            .done(function(msg) {
                $(statusBodyElement).text(msg['new_body']);
                $('#edit-reply-modal').modal('hide');
                console.log('success');
            });


    });

    $('.like-status').on('click', function(event) {
        event.preventDefault();
        //statusBody = $(this).parent().parent().prev().text();
        statusBodyElement = $(this).parent().parent().siblings('.profile-status');
        statusId = statusBodyElement.data('statusId');
        likeUrl = $(this).data('url');
        token = statusBodyElement.data('token');
        var thisLike = $(this);

        $.ajax({
                method: 'POST',
                url: likeUrl,
                data: {statusId: statusId, _token: token}
            })
            .done(function(msg) {
                if(msg['like_status'] == 'like')
                {
                    if(msg['status_type'] == 'status')
                        thisLike.text('Unlike');
                    else {
                        thisLike.text('Unlike');
                    }
                        thisLike.parent().siblings().last().children(':first').text(msg['likes_count'] + ' Likes');
                }
                else
                {
                    thisLike.text('Like');
                    thisLike.parent().siblings().last().children(':first').text(msg['likes_count'] + ' Likes');
                }
            });
    });

    $('.like-count').on('click', function (event) {
        event.preventDefault();
        $('#show-likers-modal .modal-body').text('');
        // statusBody = $(this).parent().parent().prev().text();
        // statusBodyElement = $(this).parent().parent().prev();
        // statusId = statusBodyElement.data('statusId');
        // editUrl = statusBodyElement.data('url');
        // token = statusBodyElement.data('token');
        // $('#post-body').val(statusBody);

        statusBodyElement = $(this).parent().parent().siblings('.profile-status');
        statusId = statusBodyElement.data('statusId');
        getLikersUrl = $(this).data('url');
        token = statusBodyElement.data('token');

        $.ajax({
                method: 'GET',
                url: getLikersUrl,
                data: {statusId: statusId, _token: token}
            })
            .done(function(msg) {
                // console.log(msg['users']);
                // var users = msg['users'];
                // console.log(users[0].username);
                console.log(msg['someone_likes']);
                if(msg['status_type'] == 'status')
                    $('#show-likers-modal .modal-title').text('Who liked this status');
                else
                    $('#show-likers-modal .modal-title').text('Who liked this reply');
                $('#show-likers-modal .modal-body').append(msg['html']);
                if(msg['someone_likes'] == 'false')
                    $('#show-likers-modal .modal-body').append('Sorry no one liked that before!');
                $('#show-likers-modal').modal();
            });
    });

    $('.edit-profile-form #birthDate').datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true,
        yearRange: "1930:2017",

    });

    $('.signup-form #birthDate').datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true,
        yearRange: "1930:2017",

    });

    $('#profile-reply-button').on('click', function (event) {
        event.preventDefault();
        // var url = $('.edit-post-form').data('url');
        // var token = $('.edit-post-form').data('csrf');
        // console.log($('#is-public-modal').val());
        replyForm = $("#profile-reply-form");
        var replyUrl = $("#profile-reply-form").attr('action');
        replyBody = $("#profile-reply-body");
        var replyBodyName = $("#profile-reply-body").attr('name');
        $.ajax({
                method: 'POST',
                url: replyUrl,
                data: new FormData($("#profile-reply-form")[0]),
                processData: false,
                contentType: false,
                error: function(data){
                    console.log('error');
                    var errors = data.responseJSON;
                    // console.log(errors['body']);
                    // Render the errors with js ...
                    if(errors[replyBodyName]) {
                        replyBody.parent().addClass('has-error');
                        replyBody.after('<span class="help-block">' + errors[replyBodyName] + '</span>');
                    }


                }
            })
            .done(function(msg) {
                replyBody.parent().removeClass('has-error');
                replyBody.next('span').remove();
                replyForm.before(msg['html']);
                replyBody.val('');
                console.log('success');
            });


    });

    $(".signin-button").on('click', function () {
        console.log('Hello');
        window.location.href = $(this).data('href');
    });

    $(".signup-button").on('click', function () {
        window.location.href = $(this).data('href');
    });

    //instantiate a Pusher object with our Credential's key
    var pusher = new Pusher('f8eb661929d817fc542f', {
        cluster: 'eu',
        encrypted: true
    });

    //Subscribe to the channel we specified in our Laravel Event
    var channel = pusher.subscribe('add-friend');

    //Bind a function to a Event (the full Laravel class)
    channel.bind('App\\Events\\addFriend', addMessage);

    function addMessage(data) {
        // var listItem = $("<li class='list-group-item'></li>");
        // listItem.html(data.message);
        // $('#messages').prepend(listItem);
        if(data.username != username)
        {
            $.notify({
                // options
                message: data.message,
                url: '/user/' + data.username,
                target: '_blank'
            }, {
                // settings
                type: 'info',
            });
            console.log(data.message);
        }

    }

    //Subscribe to the channel we specified in our Laravel Event
    var channel = pusher.subscribe('make-like');

    //Bind a function to a Event (the full Laravel class)
    channel.bind('App\\Events\\makeLike', addLike);

    function addLike(data) {
        // var listItem = $("<li class='list-group-item'></li>");
        // listItem.html(data.message);
        // $('#messages').prepend(listItem);
        if(data.username != username)
        {
            $.notify({
                // options
                message: data.message,
                url: '/status/' + data.status,
                target: '_blank'
            }, {
                // settings
                type: 'info',
                animate: {
                    enter: 'animated fadeOutUp',
                    exit: 'animated fadeInDown'
                },
            });
            console.log(data.message);
        }

    }

    $('.home-header').height($(window).height());


});
