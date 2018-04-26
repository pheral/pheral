<h2>Help</h2>
<div>
    <p><b style="color:#911">param</b> из аргументов Маршрута: <q><?= $paramArgument ?? '' ?></q></p>
    <p><b style="color:#911">param</b> из параметров адресной строки: <q><?= $paramRequest ?? '' ?></q></p>
    <? if (!empty($dbExample)) : ?>
        <div><b>db example</b>: <pre><? var_export($dbExample) ?></pre></div>
    <? endif; ?>
</div>