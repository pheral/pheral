<?php

$version = 'v1.0.1.15';

?><!DOCTYPE html>
<html lang="ru">
<head>
    <title>Главная | Pheral</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="/css/app.css?t=<?=$version?>" />
</head>
<body>
<header>
    <h1><a class="inverse" href="http://pheral.ru">Pheral</a></h1>
    <p>Частный код на php</p>
</header>
<nav>
    <ul>
        <li><a href="/">Главная страница</a></li>
    </ul>
</nav>
<main>
    <article>
        <h2>Добро пожаловать!</h2>
        <p>Сайт на стадии разработки T__T<br /> Заглядывайте позже, надеюсь Вам понравится :)</p>
        <section>
            <h3>Обновления</h3>
            <ul>
                <li>
                    <time datetime="2017-12-21T22:00" title="2017-12-21 22:00">21 декабря 2017</time>:
                    Добавлено взаимодействие с <a href="https://github.com/pheral/" target="_blank">GitHub</a>
                    <small>(публичные репозитории)</small>
                </li>
                <li>
                    <time datetime="2017-12-28T00:00" title="2017-12-28 00:00">28 декабря 2017</time>:
                    Добавлено взаимодействие с <a href="https://packagist.org/packages/pheral/" target="_blank">Packagist</a>
                    <small>(зависимости для композера)</small>
                </li>
                <li>
                    <time datetime="2018-01-11T16:00" title="2018-01-11 16:00">11 января 2018</time>:
                    Добавлено взаимодействие с <a href="https://travis-ci.org/pheral/" target="_blank">Travis CI</a>
                    <small>(непрерывная интеграция)</small>
                </li>
            </ul>
        </section>
        <h3>Настроение для разработки</h3>
        <figure>
            <img src="/img/salamander.jpg" width="600" height="450" />
            <figcaption>
                <i>Огнедышащее</i>
            </figcaption>
        </figure>
        <section>
            <h3>О будущем коде</h3>
            <p>Сам код на стадии эволюционного проектирования</p>
        </section>
    </article>
    <aside>
        <h3>Внешние ресурсы</h3>
        <menu>
            <li><a href="https://github.com/pheral/" target="_blank">GitHub</a></li>
            <li><a href="https://packagist.org/packages/pheral/" target="_blank">Packagist</a></li>
            <li><a href="https://travis-ci.org/pheral/" target="_blank">Travis CI</a></li>
            <li><a href="https://vk.com/pheral" target="_blank">ВКонтакте</a></li>
            <li><a href="https://twitter.com/pheral_ru" target="_blank">Twitter</a></li>
        </menu>
    </aside>
</main>
<footer>
    <p>
        <small>
            &copy;
            <time datetime="2017-12-21T21:11" title="2017-12-21 21:11">
                2017
            </time>
            &ndash;
            <time datetime="<?=date('Y-m-d\TH:i')?>" title="<?=date('Y-m-d H:i')?>">
                <?=date('d.m.Y')?>
            </time>
            <q>Pheral</q>
        </small>
    </p>
    <address>
        <a class="inverse" href="mailto:support@pheral.ru">Написать письмо</a>
    </address>
</footer>
</body>
</html>