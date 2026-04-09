<?php 
$scripts[] = JS_PATH . 'signup_for_training.js'; 
$scripts[] = JS_PATH . 'trainersByProgram.js';
$scripts[] = JS_PATH . 'deleteWindow.js';


$all_programs_result = mysqli_query($link, "SELECT name FROM training_programs");
$all_programs = getField($all_programs_result, 'name');

function getField($result, $field_name) {
    $values = [];
    $row = mysqli_fetch_assoc($result);
    while ($row) {
        array_push($values, $row[$field_name]);
        $row = mysqli_fetch_assoc($result);
    }
    return $values;
}

function getTrainersByPrograms($link) {
    $trainersByProgram = [];
    $query = "SELECT p.name as program_name, t.id, t.name as trainer_name 
              FROM trainers t 
              JOIN trainer_programs tp ON t.id = tp.trainer_id 
              JOIN training_programs p ON tp.training_name = p.name";
    $result = mysqli_query($link, $query);
    
    while ($row = mysqli_fetch_assoc($result)) {
        $trainersByProgram[$row['program_name']][] = [
            'id' => $row['id'],
            'name' => $row['trainer_name']
        ];
    }
    return json_encode($trainersByProgram);
}
?>

<main>
    <section class="schedule">
        <h1 class="schedule__title">Расписание занятий</h1>
        <table class="schedule-table">
            <thead>
                <tr>
                    <th class="schedule-table__weekday"><img src="<?= IMG_PATH ?>time_icon.png" alt="time-icon"></th>
                    <?php 
                    $today_weekday = date('N');
                    $weekday_as_number = [1 => 'Monday', 2 => 'Tuesday', 3 => 'Wednesday', 4 => 'Thursday', 5 => 'Friday', 6 => 'Saturday', 7 => 'Sunday'];
                    $week = ['Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота', 'Воскресенье'];

                    $now = new DateTime();
                    $monday = clone $now;
                    $monday->modify('monday this week');

                    for ($i = 1; $i < 8; $i++) {
                        $current_day = clone $monday;
                        $current_day->modify('+' . ($i - 1) . ' days');

                        if ($current_day->format('d.m') < $now->format('d.m')) {
                            $current_day->modify('+1 week');
                        }

                        $weekday_class = $today_weekday == $i ? 'class="schedule-table__weekday schedule-table__weekday_today"' : 'class="schedule-table__weekday"';
                        echo '<th ' . $weekday_class . '>' . $week[$i-1] . '<br>' . $current_day->format('d.m') . '</th>';
                    }
                    ?>
                </tr>
            </thead>
            <tbody>
                <?php
                for ($hour = 9; $hour < 22; $hour++) {
                    echo '<tr>';
                    echo '<td class="schedule-table__cell">' . $hour . ':00</td>';
                    
                    for ($i = 1; $i < 8; $i++) {
                        $cell_class = $today_weekday == $i ? 'class="schedule-table__cell schedule-table__cell_today' : 'class="schedule-table__cell';
                        $cell_class .= $today_weekday == $i && $hour == 21 ? '-last"' : '"';
                        $is_training = False;

                        $training_query = "SELECT * FROM training_schedule WHERE week_day = '{$weekday_as_number[$i]}'";
                        $training_result = mysqli_query($link, $training_query);
                        if ($training_result) {
                            $rows = mysqli_num_rows($training_result);

                            for ($j = 0; $j < $rows; $j++) {
                                $row = mysqli_fetch_row($training_result);
                                if ($row[2] == $hour) {
                                    ?>

                                    <td <?= $cell_class ?>>
                                        <form method="post">
                                            <input type="hidden" name="isset_training" value="<?= true ?>">
                                            <input type="hidden" name="training_in_schedule_id" value="<?= $row[0] ?>">
                                            <input type="hidden" name="training_name" value="<?= $row[3] ?>">
                                            <input type="hidden" name="people_amount" value="<?= $row[5] ?>">
                                            <input type="hidden" name="price" value="<?= $row[6] ?>">
                                            <button type="submit" class="active-training"><?= $row[3] ?></button>
                                        </form>
                                    </td>

                                    <?php
                                    $is_training = True;
                                    break;
                                }
                            }
                        }
                        if (!$is_training) {
                        ?>
                        <td <?= $cell_class ?>>
                            <form method="post">
                                <input type="hidden" name="isset_training" value="<?= false ?>">
                                <input type="hidden" name="week_day" value="<?= $weekday_as_number[$i] ?>">
                                <input type="hidden" name="start_time" value="<?= $hour ?>">
                                <button type="submit" class="new-training">Добавить</button>
                            </form>
                        </td>
                        <?php
                        }
                    }
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>

        <div class="modal-window" id="modalWindow">
            <div class="modal-window__content" id="modalContent">
                <h2 class="modal-window__title"><?= $_POST['isset_training'] ? 'Изменить занятие' : 'Добавить занятие' ?></h2>
                <div class="modal-window__buttons">
                    <form class="flex flex-col gap-1" action="index.php?action=admin/training-in-schedule" method="post">
                        <select name="training_name" class="text-black" id="trainingSelect" required>
                            <option class="text-black" value="" selected disabled>Выберите занятие</option>
                            <?php
                            foreach ($all_programs as $program) {
                                echo '<option value="' . $program . '" class="text-black"' . 
                                    ($program == $_POST['training_name'] ? ' selected' : '') . 
                                    '>' . $program . '</option>';
                            }
                            ?>
                        </select>

                        <?php
                        $training_name = $_POST['training_name'] ?? null;

                        if ($training_name) {
                            $trainers_id_result = mysqli_query($link, "SELECT trainer_id FROM trainer_programs WHERE training_name = '$training_name'");
                            $trainers_id = getField($trainers_id_result, 'trainer_id');
                            $trainers = [];
                            foreach ($trainers_id as $id) {
                                $trainer_name_result = mysqli_query($link, "SELECT name FROM trainers WHERE id = $id");
                                $trainer_name = mysqli_fetch_row($trainer_name_result)[0];
                                $trainers[$id] = $trainer_name;
                            }
                        }
                        ?>
                        <select class="text-black" name="trainer_id" id="trainerSelect" required>
                            <?php if ($trainer_name):
                                foreach ($trainers as $id => $name): ?>
                                    <option class="text-black" value="<?= $id ?>"><?= $name ?></option>
                                <?php endforeach;
                            else: ?>
                                <option class="text-black" value="" selected disabled>Сначала выберите занятие</option>
                            <?php endif; ?>
                        </select>

                        <input class="text-black" type="number" name="people_amount" min="1" max="30" value="<?= $_POST['people_amount'] ?? '' ?>" placeholder="Количество людей" required>
                        <input class="text-black" type="number" name="price" value="<?= $_POST['price'] ?? '' ?>" placeholder="Стоимость" required>

                        <?php if ($_POST['isset_training']): ?>
                            <input type="hidden" name="training_in_schedule_id" value="<?= $_POST['training_in_schedule_id'] ?? '' ?>">
                            <button class="modal-window__signup" name="operation" value="update">Сохранить</button>
                            <button type="button" id="deleteButton" class="modal-window__goback" name="operation" value="delete">Удалить</button>
                        <?php else: ?>
                            <input type="hidden" name="week_day" value="<?= $_POST['week_day'] ?>">
                            <input type="hidden" name="start_time" value="<?= $_POST['start_time'] ?>">
                            <button class="modal-window__signup" name="operation" value="create">Добавить</button>
                        <?php endif ?>
                    </form>
                </div>
                <button class="modal-window__close-icon" id="iconCloseButton"><img src="<?= IMG_PATH ?>close_icon.png" alt="close_icon"></button>
            </div>
        </div>

        <div class="modal-window" id="deleteWindow">
            <div class="modal-window__content" id="deleteContent">
                <h2 class="modal-window__title">Удалить тренировку?</h2>
                <p class="modal-window__description">Вы уверены что хотите удалить тренировку в расписании? Это повлечет за собой отмены записей пользователей!</p>
                <form class="flex flex-col gap-1" action="index.php?action=admin/training-in-schedule" method="post">
                    <input type="hidden" name="training_in_schedule_id" value="<?= $_POST['training_in_schedule_id'] ?? '' ?>">
                    <button type="submit" name="operation" value="delete">Удалить</button>
                </form>
                <button type="button" id="cancelButton">Отмена</button>
                <button class="modal-window__close-icon" id="closeIconButton"><img src="<?= IMG_PATH ?>close_icon.png" alt="close_icon"></button>
            </div>
        </div>
    </section>

</main>

<?php
if (isset($_POST['isset_training'])) {
    echo "<script>document.getElementById('modalWindow').style.display = 'flex'</script>";
    // if (isset($_POST['training_name'])) { $_POST['training_name'] = ''; } 
}
?>

<script>const trainersData = <?= getTrainersByPrograms($link) ?>;</script>