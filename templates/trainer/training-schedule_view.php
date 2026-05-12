<section class="schedule">
    <h1 class="schedule__title">Расписание занятий</h1>

    <div class="schedule__change">
        <a href="index.php?page=trainer/training-schedule&mode=wishlist" class="schedule__change-button<?= $edited ? '' : ' schedule__change-button_disable' ?>">
            <img class="img-default" src="<?= IMG_PATH ?>round_arrow.png" alt="arrow">
            <img class="img-hover" src="<?= IMG_PATH ?>round_arrow_active.png" alt="arrow">
        </a>
        <p class="schedule__change-text">Актуальное расписание</p>
        <a href="#" class="schedule__change-button schedule__change-button_disable">
            <img class="img-default" src="<?= IMG_PATH ?>round_arrow.png" alt="arrow">
            <img class="img-hover" src="<?= IMG_PATH ?>round_arrow_active.png" alt="arrow">
        </a>
    </div>
    <span class="schedule__change-description">Это актуальное расписание на неделю. Это расписание действует до конца текущего месяца.</span>
    <span class="schedule__change-description">Изменения в расписание вносятся тренерами только с 11 по 13 число каждого месяца!</span>

    <table class="schedule-table">
        <thead>
            <tr>
                <th class="schedule-table__weekday"><img src="<?= IMG_PATH ?>time_icon.png" alt="time-icon"></th>
                <?php 
                $weekday_as_number = [1 => 'Monday', 2 => 'Tuesday', 3 => 'Wednesday', 4 => 'Thursday', 5 => 'Friday', 6 => 'Saturday', 7 => 'Sunday'];
                $week = ['Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота', 'Воскресенье'];
                
                for ($i = 1; $i < 8; $i++) {
                    $weekday_class = 'class="schedule-table__weekday"';
                    echo '<th ' . $weekday_class . '>' . $week[$i-1] . '</th>';
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
                    $cell_class = 'class="schedule-table__cell"';
                    $is_training = False;

                    $training_query = "SELECT ats.* FROM all_training_schedule ats 
                        INNER JOIN training_schedule s ON s.id = ats.id
                        WHERE ats.week_day = '{$weekday_as_number[$i]}'
                        AND ats.start_time = $hour";
                    $training_result = mysqli_query($link, $training_query);
                    $training = mysqli_fetch_row($training_result);

                    if ($training) {
                        $trainer_query = "SELECT ats.trainer_id FROM all_training_schedule ats
                            INNER JOIN training_schedule s ON s.id = ats.id
                            WHERE ats.id = $training[0]";
                        $trainer_id_result = mysqli_query($link, $trainer_query);
                        $trainer_id = mysqli_fetch_row($trainer_id_result)[0];

                        if ($_SESSION['trainer_id'] == $trainer_id):
                            if ($training[9] == 0) {
                                $rating = 'Еще нет оценок';
                            } else {
                                $rating = number_format(($training[8] / $training[9]), 1, '.', '');                            
                            }                                
                            ?>

                            <td <?= $cell_class ?>>
                                <button type="button" 
                                        class="active-training"
                                        data-mode="<?= $mode ?>"
                                        data-training-id="<?= $training[0] ?>"
                                        data-training-name="<?= htmlspecialchars($training[3]) ?>"
                                        data-people-amount="<?= $training[5] ?>"
                                        data-price="<?= $training[7] ?>"
                                        data-rating="<?= $rating ?>"
                                        data-weekday="<?= $training[1] ?>"
                                        data-hour="<?= $hour ?>">
                                    <?= htmlspecialchars($training[3]) ?>
                                </button>
                            </td>
                        <?php else: ?>
                                <td <?= $cell_class ?>>
                                    <span class="disable-training"><?= htmlspecialchars($training[3]) ?></span>
                                </td>
                        <?php endif;
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
        <h2 class="modal-window__title" id="modalTitle" data-text="Занятие">Занятие</h2>
        <p class="modal-window__description" id="modalPlaces">Количество мест: </p>
        <p class="modal-window__description" id="modalPrice">Стоимость: </p>
        <p class="modal-window__description" id="modalRating">Рейтинг: </p>
        
        <div class="modal-window__buttons">
            <button class="modal-window__goback" id="goBackButton">Вернуться к расписанию</button>
        </div>
        <button class="modal-window__close-icon" id="iconCloseButton">
            <img src="<?= IMG_PATH ?>close_icon.png" alt="close_icon">
        </button>
    </div>
</div>
