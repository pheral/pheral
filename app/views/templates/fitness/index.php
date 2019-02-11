<style>
    * {margin:0;padding:0;}
    html, body {font-family:sans-serif;font-size:14px;}
    h1, h2, h3, h4, h5, h6 {margin-bottom:10px;font-variant:small-caps;}
    table {border:none;border-collapse:collapse;}
    table th, table td {border:none;border-collapse:collapse;}
    .layout {padding:5px;}
    .table th, .table td {border:1px solid #dddddd;padding:5px;}
    .table thead td,
    .table thead th {background:#f1f1f1; color:#111111;}

    .text-top th,
    .text-top td,
    .text-top {vertical-align:top;}

    .text-middle th,
    .text-middle td {vertical-align:middle;}

    .text-bottom th,
    .text-bottom td {vertical-align:bottom;}

    .text-left th,
    .text-left td {text-align:left;}

    .text-center th,
    .text-center td {text-align:center;}

    .text-right th,
    .text-right td {text-align:right;}


    .text-top {vertical-align:top !important;}
    .text-middle {vertical-align:middle !important;}
    .text-bottom {vertical-align:bottom !important;}
    .text-left {text-align:left !important;}
    .text-center {text-align:center !important;}
    .text-right {text-align:right !important;}

    .list li {margin-left:20px;}
</style>
<h2>Практические результаты</h2>
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
                        <table class="table text-top">
                            <thead class="text-center">
                                <tr>
                                    <th rowspan="2"></th>
                                    <th rowspan="2">упражнения</th>
                                    <th colspan="<?= $practice->maxAttempts; ?>">подходы</th>
                                    <th rowspan="2"></th>
                                </tr>
                                <tr>
                                    <? for ($i = 0; $i < $practice->maxAttempts; $i++) : ?>
                                        <th><?= $i + 1; ?></th>
                                    <? endfor; ?>
                                </tr>
                            </thead>
                            <tbody class="text-left">
                            <? foreach ($practice->workout->steps as $indexStep => $step) : ?>
                                <? foreach ($step->workoutExercises as $indexExercise => $workoutExercise) : ?>
                                    <? $exercise = $workoutExercise->exercise; ?>
                                    <? foreach ($exercise->units as $indexUnit => $unit) : ?>
                                        <tr>
                                            <? if (!$indexUnit) : ?>
                                                <? if (!$indexExercise) : ?>
                                                    <td <?=($step->rowspan ? 'rowspan="'.$step->rowspan.'"' : '')?>>
                                                        #<?= $indexStep + 1; ?>
                                                    </td>
                                                <? endif; ?>
                                                <td <?=($exercise->rowspan ? 'rowspan="'.$exercise->rowspan.'"' : '')?>>
                                                    <b><?= $exercise->title; ?></b>
                                                </td>
                                            <? endif; ?>
                                            <? foreach ($unit->values as $practiceValue) : ?>
                                                <td class="text-right"><?= $practiceValue->value; ?></td>
                                            <? endforeach; ?>
                                            <td><?= $unit->title; ?></td>
                                        </tr>
                                    <? endforeach; ?>
                                <? endforeach; ?>
                            <? endforeach; ?>
                            </tbody>
                        </table>
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