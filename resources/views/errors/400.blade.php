<!DOCTYPE html>
<html>
    <head>
        <title>400</title>
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

    </body>
</html>
