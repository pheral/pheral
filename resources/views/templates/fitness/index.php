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
                                    <? for ($attempt = 1; $attempt <= $practice->maxAttempts; $attempt++) : ?>
                                        <th><?= $attempt; ?></th>
                                    <? endfor; ?>
                                </tr>
                            </thead>
                            <tbody class="text-left">
                            <? foreach ($practice->workout->steps as $step) : ?>
                                <? foreach ($step->workoutExercises as $workoutExercise) : ?>
                                    <? $exercise = $workoutExercise->exercise; ?>
                                    <? foreach ($exercise->units as $unit) : ?>
                                        <tr>
                                            <? if ($unit->isFirstInExercise) : ?>
                                                <? if ($exercise->isFirstInStep) : ?>
                                                    <td <?=($step->rowspan ? 'rowspan="'.$step->rowspan.'"' : '')?>>
                                                        #<?= $step->position; ?>
                                                    </td>
                                                <? endif; ?>
                                                <td <?=($exercise->rowspan ? 'rowspan="'.$exercise->rowspan.'"' : '')?>>
                                                    <b><?= $exercise->title; ?></b>
                                                </td>
                                            <? endif; ?>
                                            <? for ($attempt = 1; $attempt <= $practice->maxAttempts; $attempt++) : ?>
                                                <td class="text-right">
                                                    <?= array_get($unit->values, $attempt, '&mdash;'); ?>
                                                </td>
                                            <? endfor; ?>
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
