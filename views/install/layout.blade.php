<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>安装信息</title>
    <link href="{{ asset('assets/install/sheets/bootstrap.min.css') }}" rel="stylesheet">
    @yield('sheets')
</head>
<body>@yield('content')</body>
<script src="{{ asset('assets/install/scripts/jquery-1.11.1.min.js') }}"></script>
<script src="{{ asset('assets/install/scripts/bootstrap.min.js') }}"></script>
@yield('script')
</html>