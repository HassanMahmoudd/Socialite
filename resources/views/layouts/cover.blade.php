<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">
<head lang="en">
    <meta charset="utf-8">
    <title>Socialite</title>
    <link rel="stylesheet" href="{{ URL::to('css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ URL::to('css/jquery-ui.min.css') }}">
    <link rel="stylesheet" href="{{ URL::to('css/jquery.mCustomScrollbar.min.css') }}">
    <link rel="stylesheet" href="{{ URL::to('css/jquery.emoji.css') }}">
    <link href="//fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href='//fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700' rel='stylesheet'>
    <link rel="stylesheet" href="{{ URL::to('css/style.css') }}">
</head>
<body>
@yield('content')
<script src="{{ URL::to('js/jquery-2.2.4.js') }}"></script>
<script src="{{ URL::to('js/bootstrap.js') }}"></script>
<script src="{{ URL::to('js/jquery-ui.min.js') }}"></script>
<script src="{{ URL::to('js/jquery.mCustomScrollbar.min.js') }}"></script>
<script src="{{ URL::to('js/jquery.emoji.js') }}"></script>
<script src="https://cdn.rawgit.com/ashleighy/emoji.js/master/emoji.js.js"></script>
<script src="https://js.pusher.com/3.1/pusher.min.js"></script>
<script src="{{ URL::to('js/bootstrap-notify.min.js') }}"></script>
<script src="{{ URL::to('js/plugins.js') }}"></script>
<script>
    $(".signin-button").on('click', function () {
        // console.log('Hello');
        window.location.href = $(this).data('href');
    });

    $(".signup-button").on('click', function () {
        window.location.href = $(this).data('href');
    });
</script>
@include('flashy::message')
</body>
</html>