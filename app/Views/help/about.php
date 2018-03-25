<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<h1>Manual :: About</h1>

<div><b>first</b>: <?= $first ?? '' ?></div>
<div><b>middle</b>: <?= $middle ?? '' ?></div>
<div><b>last</b>: <?= $last ?? '' ?></div>

<div>extra: <?= $extra ?? '' ?></div>
</body>
</html>