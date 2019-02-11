<style>
    * {margin:0;padding:0;}
    html, body {font-family:sans-serif;font-size:14px;}
    h1, h2, h3, h4, h5, h6 {margin-bottom:10px;font-variant:small-caps;}
    table {border:none;border-collapse:collapse;}
    table th, table td {border:none;border-collapse:collapse;}
    .layout {padding:5px;}
    .table th, .table td {border:1px solid #dddddd;padding:5px;}
    .text-top,
    .text-top th,
    .text-top td {vertical-align:top;}
    .text-middle,
    .text-middle th,
    .text-middle td {vertical-align:middle;}
    .text-bottom,
    .text-bottom th,
    .text-bottom td {vertical-align:bottom;}
    .text-left,
    .text-left th,
    .text-left td {text-align:left;}
    .text-center,
    .text-center th,
    .text-center td {text-align:center;}
    .text-right,
    .text-right th,
    .text-right td {text-align:right;}
    .list li {margin-left:20px;}
</style>
<h2>Тренировки</h2>
<? if (!empty($practices)) : ?>
    <div>
        <table class="table text-top">
            <thead class="text-center">
                <tr>
                    <th rowspan="2">#</th>
                    <th rowspan="2">Пользователь</th>
                    <th rowspan="2">Тренировка</th>
                    <th rowspan="2">Статус</th>
                    <th rowspan="2">Активность</th>
                    <th rowspan="2">Достижение цели</th>
                    <th rowspan="2">Описание</th>
                    <th colspan="2">Дата</th>
                </tr>
                <tr>
                    <th>начало</th>
                    <th>конец</th>
                </tr>
            </thead>
            <tbody class="text-left">
            <? foreach ($practices as $practice) : ?>
                <tr>
                    <td><?= $practice->id; ?></td>
                    <td>
                        <b><?= $practice->user->name; ?></b><br />
                        <small><?= $practice->user->email; ?></small>
                    </td>
                    <td>
                        <b><?= $practice->workout->title; ?></b><br />
                        <small><?= $practice->workout->description; ?></small>
                        <div>
                            <br />
                            <u>Упражнения:</u>
                            <ol class="list">
                            <? foreach ($practice->workout->steps as $step) : ?>
                                <li>
                                <? foreach ($step->workoutExercises as $workoutExercise) : ?>
                                    <?= $workoutExercise->exercise->title ?>
                                    <div>
                                        <? foreach ($workoutExercise->values as $practiceValue) : ?>
                                            <?= $practiceValue->value ?> (<?= $practiceValue->unit->title ?>)<br />
                                        <? endforeach; ?>
                                        <hr />
                                    </div>
                                    <br />
                                <? endforeach; ?>
                                </li>
                            <? endforeach; ?>
                            </ol>
                        </div>
                    </td>
                    <td><?= $practice->status->title; ?></td>
                    <td><?= $practice->activity->title; ?></td>
                    <td><?= $practice->activity_hit; ?>%</td>
                    <td><?= $practice->description; ?></td>
                    <td><?= $practice->start_at; ?></td>
                    <td><?= $practice->stop_at; ?></td>
                </tr>
            <? endforeach; ?>
            </tbody>
        </table>
    </div>
<? endif; ?>