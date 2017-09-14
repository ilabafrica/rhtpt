<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="/css/app.css" rel="stylesheet">

    <!-- Custom Font -->
    <link rel="stylesheet" href="{{ asset('css/font.css') }}">
    <!-- Custom Styling -->
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <style>
        .form-control:focus 
        {
            border-color: #18bc9c;
            outline: none;
            border-width:2px;
        }
        .form-control 
        {
            border-width:2px;
        }
        .loginArea
        {
            color:#333;
            margin-top:15px;
            padding: 15px;
            border-radius: 3px 
        }
        .card {
            position: relative;
            display: block;
            margin-bottom: .75rem;
            border: 1px solid #e5e5e5;
            border-radius: .25rem;
        }

        .card-block {
            padding: 1.25rem;
        }

        .card-title {
            margin-bottom: .75rem;
        }
    </style>  
    <!-- Scripts -->
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
</head>
<body>
    <div id="app">

        @yield('content')
    </div>

    <!-- Scripts -->
    <script src="/js/app.js"></script>
</body>
</html>
