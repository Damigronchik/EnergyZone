<?php 
$scripts[] = JS_PATH . 'signup_for_training.js'; 

require SRC_PATH . 'user/user-trainings_check.php';

$unique_free_trainings = [];
if ($_SESSION['in_account']) {
    $user_mail = $_SESSION['user_mail'];
    $free_trainings = [];
    
    $sub_ids_result = mysqli_query($link, "SELECT subscription_id FROM user_subscriptions WHERE user_mail = '$user_mail'");
    $sub_ids_count = mysqli_num_rows($sub_ids_result);
    for ($i = 0; $i < $sub_ids_count; $i++) {
        $sub_id = mysqli_fetch_row($sub_ids_result)[0];
        $sub_trainings = mysqli_query($link, "SELECT training_name FROM subscription_programs WHERE subscription_id = $sub_id");

        while ($row = mysqli_fetch_assoc($sub_trainings)) {
            array_push($free_trainings, $row['training_name']);
        }
    }

    $unique_free_trainings = array_unique($free_trainings);
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
                $now_hour = date('H');
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
                                    $trainer_id_result = mysqli_query($link, "SELECT trainer_id FROM training_schedule WHERE id = $row[0]");
                                    $trainer_id = mysqli_fetch_row($trainer_id_result)[0];
                                    $trainer_name_result = mysqli_query($link, "SELECT name FROM trainers WHERE id = $trainer_id");
                                    $trainer_name = mysqli_fetch_row($trainer_name_result)[0];

                                    if ($row[9] == 0) {
                                        $rating = 'Еще нет оценок';
                                    } else {
                                        $rating = round(($row[8] / $row[9]), 1);
                                    }

                                    $is_free = in_array($row[3], $unique_free_trainings) ? true : false;

                                    $is_disabled = $hour < $now_hour && $today_weekday == $i ? true : false;
                                    ?>

                                    <td <?= $cell_class ?>>
                                        <form method="post">
                                            <input type="hidden" name="training_in_schedule_id" value="<?= $row[0] ?>">
                                            <input type="hidden" name="training_weekday" value="<?= $row[1] ?>">
                                            <input type="hidden" name="hour" value="<?= $hour ?>">
                                            <input type="hidden" name="training_name" value="<?= $row[3] ?>">
                                            <input type="hidden" name="trainer_name" value="<?= $trainer_name ?>">
                                            <input type="hidden" name="people_amount" value="<?= $row[5] ?>">
                                            <input type="hidden" name="remaining_amount" value="<?= $row[6] ?>">
                                            <input type="hidden" name="price" value="<?= $is_free ? 0 : $row[7] ?>">
                                            <input type="hidden" name="rating" value="<?= $rating ?>">
                                            <input type="hidden" name="is_disabled" value="<?= $is_disabled ?>">
                                            <button type="submit" class="active-training" ?><?= $row[3] ?></button>
                                        </form>
                                    </td>

                                    <?php
                                    $is_training = True;
                                    break;
                                }
                            }
                        }
                        if (!$is_training) {
                            echo '<td ' . $cell_class . '></td>';
                        }
                    }
                    echo '</tr>';
                }                
                ?>
            </tbody>
        </table>

        <div class="modal-window" id="modalWindow">
            <div class="modal-window__content" id="modalContent">
                <h2 class="modal-window__title">Занятие "<?= $_POST['training_name'] ?? '' ?>"</h2>
                <p class="modal-window__description">Тренер: <?= $_POST['trainer_name'] ?? '' ?></p>
                <?php if (!$_POST['is_disabled']): ?>
                    <p class="modal-window__description">Осталось мест: <?= $_POST['remaining_amount'] ?> из <?= $_POST['people_amount'] ?></p>
                <?php endif; ?>
                <p class="modal-window__description">Стоимость: <?= $_POST['price'] == 0 ? 'Бесплатно (включено в действующий абонемент)' : $_POST['price'] . 'руб.' ?></p>
                <p class="modal-window__description">Рейтинг: <?= $_POST['rating'] ?></p>
                <div class="modal-window__buttons">
                    <?php if ($_POST['is_disabled'] || $_POST['remaining_amount'] == 0): ?>
                        <button disabled>Запись невозможна</button>
                    <?php else: ?>
                        <form method="post">
                            <input type="hidden" name="training_id_for_signup" value="<?= $_POST['training_in_schedule_id'] ?>">
                            <input type="hidden" name="training_weekday" value="<?= $_POST['training_weekday'] ?>">
                            <input type="hidden" name="hour" value="<?= $_POST['hour'] ?>">
                            <?php if (!isset($_SESSION['trainer_logged_in'])): ?>
                                <button class="modal-window__signup">Записаться</button>
                            <?php endif; ?>
                        </form>
                    <?php endif; ?>
                    <button class="modal-window__goback" id="goBackButton">Вернуться к расписанию</button>
                </div>
                <button class="modal-window__close-icon" id="iconCloseButton"><img src="<?= IMG_PATH ?>close_icon.png" alt="close_icon"></button>
            </div>
        </div>

        <div class="modal-window" id="successWindow">
            <div class="modal-window__content" id="successContent">
                <h2 class="modal-window__title">Вы успешно записаны!</h2>
                <p class="modal-window__description">Оплата прошла успешно! В личном кабинете можно увидеть все свои записи на тренировки и при необходимости отменить их.</p>
                <div class="modal-window__buttons">
                    <button class="modal-window__homepage"><a href="index.php">Вернуться на главную</a></button>
                    <button class="modal-window__account"><a href="index.php?page=account">Перейти в личный кабинет</a></button>
                </div>
                <button class="modal-window__close-icon" id="goSchedule"><img src="<?= IMG_PATH ?>close_icon.png" alt="close_icon"></button>
            </div>
        </div>

        <div class="modal-window" id="failWindow">
            <div class="modal-window__content" id="failContent">
                <h2 class="modal-window__title">Запись невозможна!</h2>
                <p class="modal-window__description">Вы уже записаны на данную тренировку</p>
                <button class="modal-window__goback" id="closeFailButton">Вернуться к расписанию</button>
                <button class="modal-window__close-icon" id="closeFailIcon"><img src="<?= IMG_PATH ?>close_icon.png" alt="close_icon"></button>
            </div>
        </div>
    </section>
</main>

<?php
if ($_SESSION['REQUEST_METHOD'] = 'POST' && !empty($_POST['training_name'])) {
    echo "<script>document.getElementById('modalWindow').style.display = 'flex'</script>";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['training_id_for_signup'])) {
    if ($_SESSION['in_account']) {
        $user_mail = $_SESSION['user_mail'];
        $training_id = $_POST['training_id_for_signup'];
        
        $user_train_query = "SELECT * FROM user_trainings WHERE user_mail = '$user_mail' AND id_train_schedule = $training_id";
        $user_train_result = mysqli_query($link, $user_train_query);

        if (mysqli_num_rows($user_train_result) == 0) {
            $hour = $_POST['hour'];
            $training_weekday = $_POST['training_weekday'];
            $training_day_index = array_search($training_weekday, $weekday_as_number);

            $current_datetime = clone $monday;
            $current_datetime->modify('+' . ($training_day_index-1) . ' days');
            if ($current_datetime->format('d.m') < $now->format('d.m')) { $current_datetime->modify('+1 week'); }
            $current_datetime->setTime($hour, 0, 0);
            $formatted_datetime = $current_datetime->format('Y-m-d H:i:s');

            $add_train_query = "INSERT INTO user_trainings (user_mail, id_train_schedule, training_datetime) VALUES ('$user_mail', $training_id, '$formatted_datetime')";
            $add_train_result = mysqli_query($link, $add_train_query);
            
            echo "<script>document.getElementById('modalWindow').style.display = 'none'</script>";
            echo "<script>document.getElementById('successWindow').style.display = 'flex'</script>";
        } else {
            echo "<script>document.getElementById('modalWindow').style.display = 'none'</script>";
            echo "<script>document.getElementById('failWindow').style.display = 'flex'</script>";
            $_POST['training_id_for_signup'] = null;
        }
    } else {
        echo "<script>document.getElementById('modalWindow').style.display = 'none'</script>";
        echo "<script>document.getElementById('logModalWindow').style.display = 'flex'</script>";
    }
}
?>