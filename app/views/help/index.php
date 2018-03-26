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
<h1>Manual</h1>
<div>Page: <?= $page ?? '' ?></div>
<form name="test" action="" method="post" enctype="multipart/form-data">
    <input type="file" name="asdf" /><br />
    <input type="file" name="qwerty[]" /><br />
    <input type="file" name="qwerty[]" /><br />
    <button type="submit">Отправить</button>
</form>
</body>
</html>