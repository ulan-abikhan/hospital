<!DOCTYPE html>
<html>
<head>
    <title>{{ $name }}</title>
</head>
<body>
    <p>Hello {{ $name }}! Verify your via link:</p>

    <a href="{{ $link }}">Click here</a>

    <br>

    <p>If this are not you discard this action via link:</p>

    <a href="{{ $discard }}">Click here</a>

</body>
</html>