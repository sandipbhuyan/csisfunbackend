<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Laravel Installer</title>
    <!-- Bootstrap core CSS -->
    <link href="{{mix('css/app.css')}}" rel="stylesheet">
    <!-- Custom styles for this template -->
    @yield('stylesheets')
</head>
<body>
<!-- Page Content -->
<div class="container">
    @yield('content')
</div>

<script src="{{asset('popper/popper.min.js')}}"></script>
<script src="{{mix('js/app.js')}}"></script>
@yield('scripts')
</body>
</html>
