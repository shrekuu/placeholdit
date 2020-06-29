<!DOCTYPE html>
<html>
    <head>
        <title>error</title>

        <link href="https://cdn.bootcdn.net/ajax/libs/twitter-bootstrap/3.4.1/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
        
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if ($errMsg)
            <div class="alert alert-danger">
                {{ $errMsg }}
            </div>
        @endif

    </body>
</html>
