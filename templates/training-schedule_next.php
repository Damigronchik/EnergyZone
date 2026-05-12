<section class="schedule">
    <h1 class="schedule__title">Расписание занятий</h1>

    <div class="schedule__change">
        <a href="index.php?page=training-schedule&mode=current" class="schedule__change-button">
            <img class="img-default" src="<?= IMG_PATH ?>round_arrow.png" alt="arrow">
            <img class="img-hover" src="<?= IMG_PATH ?>round_arrow_active.png" alt="arrow">
        </a>
        <p class="schedule__change-text">Расписание на <?= $monday->modify('+1 week')->format('d.m') . '-' . (clone $monday)->modify('+6 days')->format('d.m') ?></p>
        <a href="#" class="schedule__change-button schedule__change-button_disable">
            <img class="img-default" src="<?= IMG_PATH ?>round_arrow.png" alt="arrow">
            <img class="img-hover" src="<?= IMG_PATH ?>round_arrow_active.png" alt="arrow">
        </a>
    </div>

    <table class="schedule-table">
        <thead>
            <tr>
                <th class="schedule-table__weekday"><img src="<?= IMG_PATH ?>time_icon.png" alt="time-icon"></th>
                <?php
                for ($i = 1; $i < 8; $i++) {
                    $current_day = clone $monday;
                    $current_day->modify('+' . ($i - 1) . ' days');

                    $weekday_class = 'class="schedule-table__weekday"';
                    echo '<th ' . $weekday_class . '>' . $week[$i-1] . '<br>' . $current_day->format('d.m') . '</th>';
                }
                ?>
            </tr>
        </thead>
        <tbody>
            <?php
            $now_hour = date('H');
            $monday_month = (clone $monday)->modify('-1 week')->format('m');
            for ($hour = 9; $hour < 22; $hour++) {
                echo '<tr>';
                echo '<td class="schedule-table__cell">' . $hour . ':00</td>';
                
                for ($i = 1; $i < 8; $i++) {
                    $cell_class = 'class="schedule-table__cell"';
                    $is_training = False;

                    $current_day = clone $monday;
                    $current_day->modify('+' . ($i - 1) . ' days');
                    $table_name = $monday_month == $current_day->format('m') ? 'training_schedule' : 'new_schedule';

                    $training_query = "SELECT ats.* FROM all_training_schedule ats 
                        INNER JOIN $table_name s ON s.id = ats.id
                        WHERE ats.week_day = '{$weekday_as_number[$i]}'
                        AND ats.start_time = $hour";
                    $training_result = mysqli_query($link, $training_query);
                    $training = mysqli_fetch_row($training_result);

                    if ($training) {
                        $trainer_query = "SELECT ats.trainer_id FROM all_training_schedule ats
                            INNER JOIN $table_name s ON s.id = ats.id
                            WHERE ats.id = $training[0]";
                        $trainer_id_result = mysqli_query($link, $trainer_query);
                        $trainer_id = mysqli_fetch_row($trainer_id_result)[0];
                        $trainer_name_result = mysqli_query($link, "SELECT name FROM trainers WHERE id = $trainer_id");
                        $trainer_name = mysqli_fetch_row($trainer_name_result)[0];

                        if ($training[9] == 0) {
                            $rating = 'Еще нет оценок';
                        } else {
                            $rating = round(($training[8] / $training[9]), 1);
                        }

                        $is_free = in_array($training[3], $unique_free_trainings) ? true : false;
                        $is_disabled = $hour < $now_hour && $today_weekday == $i ? true : false;
                        ?>

                        <td <?= $cell_class ?>>
                            <button type="button" 
                                    class="active-training"
                                    data-training-id="<?= $training[0] ?>"
                                    data-training-name="<?= htmlspecialchars($training[3]) ?>"
                                    data-trainer-name="<?= htmlspecialchars($trainer_name) ?>"
                                    data-people-amount="<?= $training[5] ?>"
                                    data-remaining-amount="<?= $training[6] ?>"
                                    data-price="<?= $is_free ? 0 : $training[7] ?>"
                                    data-rating="<?= htmlspecialchars($rating) ?>"
                                    data-is-disabled="<?= $is_disabled ? '1' : '0' ?>"
                                    data-is-free="<?= $is_free ? '1' : '0' ?>"
                                    data-weekday="<?= $training[1] ?>"
                                    data-hour="<?= $hour ?>">
                                <?= htmlspecialchars($training[3]) ?>
                            </button>
                        </td>

                        <?php
                        $is_training = True;
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
</section>

<div class="modal-window" id="modalWindow">
    <div class="modal-window__content" id="modalContent">
        <h2 class="modal-window__title" id="modalTitle" dta-text="Занятие">Занятие</h2>
        <p class="modal-window__description" id="modalTrainer">Тренер: </p>
        <p class="modal-window__description" id="modalPlaces">Осталось мест: </p>
        <p class="modal-window__description" id="modalPrice">Стоимость: </p>
        <p class="modal-window__description" id="modalRating">Рейтинг: </p>
        
        <div class="modal-window__buttons">
            <form id="signupForm" method="post">
                <input type="hidden" name="training_id_for_signup" id="signupTrainingId">
                <input type="hidden" name="training_weekday" id="signupWeekday">
                <input type="hidden" name="hour" id="signupHour">
                <button type="submit" class="modal-window__signup" id="signupButton">Записаться</button>
            </form>
            <button class="modal-window__goback" id="goBackButton">Вернуться к расписанию</button>
        </div>
        <button class="modal-window__close-icon" id="iconCloseButton">
            <img src="<?= IMG_PATH ?>close_icon.png" alt="close_icon">
        </button>
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
        <div class="modal-window__buttons">
            <button class="modal-window__goback" id="closeFailButton">Вернуться к расписанию</button>
            <button class="modal-window__close-icon" id="closeFailIcon"><img src="<?= IMG_PATH ?>close_icon.png" alt="close_icon"></button>            
        </div>
    </div>
</div>
