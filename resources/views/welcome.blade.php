<!DOCTYPE html>
<html>
    <head>
        <title>Laravel</title>

        <style>
            * {
                font-family: Arial, "PingFang SC", 'Microsoft Yahei', sans-serif;
            }
            .container {
                width: 300px;
                margin: 50px auto;
                color: #444;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="content">
                <p>举例: {{ config('app.url')  }}/300x200/1</p>
                <p>"300x200" 为宽高</p>
                <p>"1" 为使图片唯一的 key</p>
            </div>
        </div>
    </body>
</html>
