<h2>Отчёты</h2>

<? if (!empty($tasks)) : ?>
    <div>
        <h3>Отчёт по задачам</h3>
        <table border="1">
            <thead>
            <tr>
                <th rowspan="2">#</th>
                <th rowspan="2">Заголовок</th>
                <th rowspan="2">Описание</th>
                <th colspan="2">Период</th>
            </tr>
            <tr>
                <th>от</th>
                <th>до</th>
            </tr>
            </thead>
            <tbody>
            <? foreach ($tasks as $task) : ?>
                <?
                if ($task->is_todo) {
                    $color = 'lightskyblue';
                } elseif ($task->is_work) {
                    $color = 'lightsalmon';
                } elseif ($task->is_hold) {
                    $color = 'lightgray';
                } elseif ($task->is_done) {
                    $color = 'lightgreen';
                } else {
                    $color = 'white';
                }
                ?>
                <tr style="background-color:<?= $color; ?>">
                    <td><?= $task->id; ?></td>
                    <td><?= $task->title; ?></td>
                    <td><?= $task->description; ?></td>
                    <td><?= $task->start_at; ?></td>
                    <td><?= $task->stop_at; ?></td>
                </tr>
            <? endforeach; ?>
            </tbody>
        </table>
    </div>
<? endif; ?>