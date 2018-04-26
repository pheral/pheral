<h2>Help</h2>
<div>
    <p><b style="color:#911">param</b> из аргументов Маршрута: <q><?= $paramArgument ?? '' ?></q></p>
    <p><b style="color:#911">param</b> из параметров адресной строки: <q><?= $paramRequest ?? '' ?></q></p>
    <? if (!empty($dbExampleAdd)) : ?>
        <div><b>db example add</b>: <pre><? var_export($dbExampleAdd) ?></pre></div>
    <? endif; ?>
    <? if (!empty($dbExampleGet)) : ?>
        <div><b>db example get</b>: <pre><? var_export($dbExampleGet) ?></pre></div>
    <? endif; ?>
</div>