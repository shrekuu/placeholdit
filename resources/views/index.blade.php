<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>蓝石创想 | 图床</title>
    <link rel="stylesheet" href="/vendor/css/github-markdown.css">
    <style>
        body {
            box-sizing: border-box;
            min-width: 200px;
            max-width: 980px;
            margin: 0 auto;
            padding: 45px;
        }
    </style>
</head>
<body>
<article class="markdown-body">
    {!! \Markdown::convertToHtml(file_get_contents(base_path() . '/readme.md')) !!}
</article>
</body>
</html>
