<!doctype html>
<html lang="ru">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Pheral Fitness</title>
        <link href="/css/application.css" rel="stylesheet" type="text/css" />
    </head>
    <body>
        <div class="nav-panel">
            <div class="pull-right">
                <a href="<?= url()->path('/fitness/auth/logout') ?>">Выйти</a>
            </div>
        </div>
        <div class="layout">
            <h1>Fitness</h1>
            <div>
                <?= $content ?? '' ?>
            </div>
        </div>
        <script src="/js/application.js" type="text/javascript"></script>
    </body>
</html>