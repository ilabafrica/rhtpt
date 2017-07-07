<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{!! Config::get('cms.name') !!}</title>

        <meta name="description" content="">
        <!-- Bootstrap core CSS -->
        <link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet">

        <!-- Custom Font -->
        <link rel="stylesheet" href="{{ asset('css/font.css') }}">

        <!-- Custom Styling -->
        <link href="{{ asset('css/custom.css') }}" rel="stylesheet">

        <style>
            html, body {
                height: 100%;
            }

            body {
                margin: 0;
                padding: 0;
                width: 100%;
                color: #B0BEC5;
                display: table;
            }

            .container {
                text-align: center;
                display: table-cell;
                vertical-align: middle;
            }

            .content {
                text-align: center;
                display: inline-block;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="content">
              <div class="error-page">
                  @yield('content')
              </div>
            </div>
        </div>
    </body>
</html>
