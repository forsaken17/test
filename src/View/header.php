<html>
    <head>
        <title>TT application</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="/css/styles.css" />

        <script type="text/javascript" src="/js/jquery-2.1.4.min.js"></script>
        <script type="text/javascript" src="/js/app.js"></script>
    </head>
    <body>
        <?php if (isLogged()): ?>
            <div class="logout"><a href="/logout">logout</a></div>
        <?php endif; ?>